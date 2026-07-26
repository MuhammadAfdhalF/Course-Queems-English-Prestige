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
            $passingGrade = $totalScore > 0 ? (int) round(($passingScore / $totalScore) * 100) : 0;
        } else {
            $passingScore = null;
            $passingGrade = 0;
        }

        $normalized = [
            'total_score' => $totalScore,
            'result_mode' => $resultMode,
            'passing_score' => $passingScore,
            'passing_grade' => $passingGrade,
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

        return $requestedActive;
    }
}
