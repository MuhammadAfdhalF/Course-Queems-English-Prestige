<?php

namespace App\Services;

use App\Enums\AssessmentResultMode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AssessmentConfigService
{
    /**
     * Validate and normalize assessment configuration data.
     *
     * @param Request $request
     * @param mixed|null $assessment Instance of FinalExam, ModulePractice, or FreeTest
     * @param bool $supportsAttemptLimit Whether this assessment type supports attempt limits (false for FreeTest)
     * @return array
     * @throws ValidationException
     */
    public function validateAndNormalize(Request $request, mixed $assessment = null, bool $supportsAttemptLimit = true): array
    {
        $resultMode = $request->input('result_mode', AssessmentResultMode::PASS_FAIL->value);

        $rules = [
            'result_mode' => ['required', 'string', Rule::in([AssessmentResultMode::PASS_FAIL->value, AssessmentResultMode::SCORE_ONLY->value])],
            'total_score' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
        ];

        if ($resultMode === AssessmentResultMode::PASS_FAIL->value) {
            $totalScore = (float) $request->input('total_score', 0);
            $rules['passing_score'] = ['required', 'numeric', 'min:0.01', "max:{$totalScore}"];
        } else {
            $rules['passing_score'] = ['nullable'];
        }

        if ($supportsAttemptLimit) {
            $rules['attempt_mode'] = ['required', 'string', Rule::in(['one', 'multiple', 'unlimited'])];
            if ($request->input('attempt_mode') === 'multiple') {
                $rules['max_attempts'] = ['required', 'integer', 'min:2'];
            } else {
                $rules['max_attempts'] = ['nullable'];
            }
        }

        $validator = validator($request->all(), $rules, [
            'passing_score.max' => 'The passing score cannot exceed total score.',
            'passing_score.required' => 'Passing grade / minimum score is required when Result Type is Pass / Fail.',
            'max_attempts.min' => 'Multiple attempts limit must be at least 2.',
            'max_attempts.required' => 'Maximum attempts is required when Multiple Attempts is selected.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        $totalScore = round((float) $validated['total_score'], 2);

        if ($resultMode === AssessmentResultMode::PASS_FAIL->value) {
            $passingScore = round((float) $validated['passing_score'], 2);
        } else {
            $passingScore = null;
        }

        $normalized = [
            'total_score' => $totalScore,
            'result_mode' => $resultMode,
            'passing_score' => $passingScore,
        ];

        if ($supportsAttemptLimit) {
            $attemptMode = $validated['attempt_mode'];
            if ($attemptMode === 'one') {
                $maxAttempts = 1;
            } elseif ($attemptMode === 'multiple') {
                $maxAttempts = (int) $validated['max_attempts'];
            } else {
                $maxAttempts = null;
            }

            $normalized['max_attempts'] = $maxAttempts;
        }

        // Config Mutability and Guard checks for existing assessments
        if ($assessment && $assessment->exists) {
            $this->applyMutabilityAndAttemptGuards($assessment, $normalized, $supportsAttemptLimit);
        }

        return $normalized;
    }

    /**
     * Check config mutability and max_attempts reduction guards.
     */
    private function applyMutabilityAndAttemptGuards(mixed $assessment, array &$normalized, bool $supportsAttemptLimit): void
    {
        $hasHistory = false;
        $maxUsed = 0;

        if (method_exists($assessment, 'attempts') && $assessment->attempts()->exists()) {
            $hasHistory = true;
            if ($supportsAttemptLimit) {
                $maxUsed = (int) ($assessment->attempts()
                    ->selectRaw('student_id, COUNT(*) as cnt')
                    ->groupBy('student_id')
                    ->pluck('cnt')
                    ->max() ?? 0);
            }
        } elseif (method_exists($assessment, 'results') && $assessment->results()->exists()) {
            $hasHistory = true;
        }

        if ($hasHistory) {
            $origTotal = round((float) $assessment->total_score, 2);
            $origMode = $assessment->result_mode instanceof AssessmentResultMode
                ? $assessment->result_mode->value
                : (string) $assessment->result_mode;
            $origPassing = $assessment->passing_score !== null ? round((float) $assessment->passing_score, 2) : null;

            if (
                $normalized['total_score'] !== $origTotal ||
                $normalized['result_mode'] !== $origMode ||
                $normalized['passing_score'] !== $origPassing
            ) {
                throw ValidationException::withMessages([
                    'total_score' => ['Scoring configuration cannot be changed because this assessment already has student attempts/results.'],
                ]);
            }

            if ($supportsAttemptLimit && isset($normalized['max_attempts'])) {
                $newMax = $normalized['max_attempts'];
                if ($newMax !== null && $maxUsed > 0 && $newMax < $maxUsed) {
                    throw ValidationException::withMessages([
                        'max_attempts' => ["Maximum attempts cannot be lower than the number of attempts already used by a student ({$maxUsed})."],
                    ]);
                }
            }
        }
    }

    /**
     * Determine if activation should be forced false on create or update.
     */
    public function resolveIsActiveStatus(Request $request, mixed $assessment = null, array $normalizedConfig = []): bool
    {
        $requestedActive = $request->boolean('is_active');

        // On create: always force false (questions not yet allocated)
        if (!$assessment || !$assessment->exists) {
            return false;
        }

        // On update: check if scoring configuration changed
        if (!empty($normalizedConfig)) {
            $origTotal = round((float) $assessment->total_score, 2);
            $origMode = $assessment->result_mode instanceof AssessmentResultMode
                ? $assessment->result_mode->value
                : (string) $assessment->result_mode;
            $origPassing = $assessment->passing_score !== null ? round((float) $assessment->passing_score, 2) : null;

            if (
                $normalizedConfig['total_score'] !== $origTotal ||
                $normalizedConfig['result_mode'] !== $origMode ||
                $normalizedConfig['passing_score'] !== $origPassing
            ) {
                return false;
            }
        }

        // If activating, validate activation rules
        if ($requestedActive) {
            $this->validateActivation($assessment);
        }

        return $requestedActive;
    }

    /**
     * Calculate total score of active questions for an assessment.
     */
    public function calculateAllocatedScore(mixed $assessment): float
    {
        if (!$assessment || !$assessment->exists) {
            return 0.0;
        }

        $sum = $assessment->questions()
            ->where('is_active', true)
            ->sum('score');

        return round((float) $sum, 2);
    }

    /**
     * Calculate remaining unallocated score for an assessment.
     */
    public function calculateRemainingScore(mixed $assessment): float
    {
        if (!$assessment || !$assessment->exists) {
            return 0.0;
        }

        $totalScore = round((float) $assessment->total_score, 2);
        $allocated = $this->calculateAllocatedScore($assessment);

        return round($totalScore - $allocated, 2);
    }

    /**
     * Check if an assessment has student attempts or results.
     */
    public function hasHistory(mixed $assessment): bool
    {
        if (!$assessment || !$assessment->exists) {
            return false;
        }

        if (method_exists($assessment, 'attempts') && $assessment->attempts()->exists()) {
            return true;
        }

        if (method_exists($assessment, 'answers') && $assessment->answers()->exists()) {
            return true;
        }

        if (method_exists($assessment, 'results') && $assessment->results()->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Ensure assessment is not locked by student attempts/results.
     *
     * @throws ValidationException
     */
    public function ensureNotLocked(mixed $assessment): void
    {
        if ($this->hasHistory($assessment)) {
            throw ValidationException::withMessages([
                'locked' => ['Questions cannot be changed because this assessment already has student attempts/results.'],
            ]);
        }
    }

    /**
     * Get complete readiness status and score allocation metrics.
     */
    public function getReadinessStatus(mixed $assessment): array
    {
        if (!$assessment || !$assessment->exists) {
            return [
                'total_score' => 0.0,
                'allocated_score' => 0.0,
                'remaining_score' => 0.0,
                'active_questions_count' => 0,
                'total_questions_count' => 0,
                'status' => 'incomplete',
                'status_label' => 'Incomplete',
                'is_activatable' => false,
                'reasons' => ['Assessment does not exist.'],
                'is_locked' => false,
            ];
        }

        $totalScore = round((float) $assessment->total_score, 2);
        $allocatedScore = $this->calculateAllocatedScore($assessment);
        $remainingScore = round($totalScore - $allocatedScore, 2);

        $activeQuestionsCount = $assessment->questions()->where('is_active', true)->count();
        $totalQuestionsCount = $assessment->questions()->count();
        $isLocked = $this->hasHistory($assessment);

        $resultMode = $assessment->result_mode instanceof AssessmentResultMode
            ? $assessment->result_mode->value
            : (string) $assessment->result_mode;

        $passingScore = $assessment->passing_score !== null ? round((float) $assessment->passing_score, 2) : null;

        $reasons = [];

        if ($totalScore <= 0) {
            $reasons[] = 'Total score must be greater than 0.';
        }

        if ($activeQuestionsCount === 0) {
            $reasons[] = 'At least 1 active question is required.';
        }

        if ($allocatedScore > $totalScore) {
            $overAmount = round($allocatedScore - $totalScore, 2);
            $reasons[] = "Active question scores exceed total score by {$overAmount}.";
        } elseif ($allocatedScore < $totalScore) {
            $reasons[] = "Active question scores ({$allocatedScore}) do not match total score ({$totalScore}). Remaining: {$remainingScore}.";
        }

        if (!in_array($resultMode, [AssessmentResultMode::PASS_FAIL->value, AssessmentResultMode::SCORE_ONLY->value], true)) {
            $reasons[] = 'Result mode is invalid.';
        }

        if ($resultMode === AssessmentResultMode::PASS_FAIL->value) {
            if ($passingScore === null || $passingScore <= 0 || $passingScore > $totalScore) {
                $reasons[] = 'Minimum passing score is invalid or exceeds total score.';
            }
        } elseif ($resultMode === AssessmentResultMode::SCORE_ONLY->value) {
            if ($passingScore !== null) {
                $reasons[] = 'Score Only mode must not have a passing score.';
            }
        }

        if ($allocatedScore > $totalScore) {
            $status = 'invalid_over_allocated';
            $statusLabel = 'Over Allocated';
        } elseif ($allocatedScore === $totalScore && empty($reasons)) {
            $status = 'ready';
            $statusLabel = 'Ready to Activate';
        } else {
            $status = 'incomplete';
            $statusLabel = 'Incomplete';
        }

        return [
            'total_score' => $totalScore,
            'allocated_score' => $allocatedScore,
            'remaining_score' => $remainingScore,
            'active_questions_count' => $activeQuestionsCount,
            'total_questions_count' => $totalQuestionsCount,
            'status' => $status,
            'status_label' => $statusLabel,
            'is_activatable' => empty($reasons),
            'reasons' => $reasons,
            'is_locked' => $isLocked,
        ];
    }

    /**
     * Validate prospective score change to prevent over-allocation.
     *
     * @throws ValidationException
     */
    public function validateProspectiveScore(mixed $assessment, float $prospectiveScore, bool $willBeActive, mixed $targetQuestion = null): void
    {
        if (!$assessment || !$assessment->exists) {
            return;
        }

        $totalScore = round((float) $assessment->total_score, 2);

        // Calculate allocated score of other active questions
        $query = $assessment->questions()->where('is_active', true);
        if ($targetQuestion && $targetQuestion->exists) {
            $query->where('id', '!=', $targetQuestion->id);
        }

        $otherAllocated = round((float) $query->sum('score'), 2);

        if ($willBeActive) {
            $prospectiveAllocated = round($otherAllocated + round($prospectiveScore, 2), 2);
        } else {
            $prospectiveAllocated = $otherAllocated;
        }

        if ($prospectiveAllocated > $totalScore) {
            $remainingAllowed = max(0.0, round($totalScore - $otherAllocated, 2));
            throw ValidationException::withMessages([
                'score' => ["Question score exceeds the remaining assessment score. Maximum allowed: {$remainingAllowed}."],
            ]);
        }
    }

    /**
     * Handle auto-deactivation if post-mutation allocated score mismatch total score.
     *
     * @return bool True if assessment was auto-deactivated.
     */
    public function handlePostMutationDeactivation(mixed $assessment): bool
    {
        return $this->syncAssessmentReadinessState($assessment);
    }

    /**
     * Synchronize assessment readiness state: automatically activate if exact allocation and valid,
     * or deactivate if allocation is incomplete / mismatch.
     *
     * @return bool True if assessment activation status changed.
     */
    public function syncAssessmentReadinessState(mixed $assessment): bool
    {
        if (!$assessment || !$assessment->exists) {
            return false;
        }

        $readiness = $this->getReadinessStatus($assessment);
        $shouldBeActive = ($readiness['status'] === 'ready');

        if ((bool) $assessment->is_active !== $shouldBeActive) {
            $assessment->update(['is_active' => $shouldBeActive]);
            return true;
        }

        return false;
    }

    /**
     * Validate assessment activation rules.
     *
     * @throws ValidationException
     */
    public function validateActivation(mixed $assessment): void
    {
        $readiness = $this->getReadinessStatus($assessment);

        if (!$readiness['is_activatable']) {
            $message = implode(' ', $readiness['reasons']);
            throw ValidationException::withMessages([
                'is_active' => ["Assessment cannot be activated. {$message}"],
            ]);
        }
    }
}
