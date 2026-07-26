<?php

namespace App\Services;

use App\Enums\AssessmentResultMode;
use App\Models\FreeTest;
use Illuminate\Validation\ValidationException;

class AssessmentScoringService
{
    /**
     * Create snapshot payload array from master assessment instance.
     *
     * @param mixed $assessment FinalExam, ModulePractice, or FreeTest
     * @return array
     */
    public function createSnapshotPayload(mixed $assessment): array
    {
        $maxScore = round((float) ($assessment->total_score ?? 100.00), 2);
        if ($maxScore <= 0) {
            throw ValidationException::withMessages([
                'assessment' => ['Assessment total score must be greater than zero.'],
            ]);
        }

        $rawMode = $assessment->result_mode;
        $resultMode = $rawMode instanceof AssessmentResultMode
            ? $rawMode->value
            : (string) ($rawMode ?? AssessmentResultMode::PASS_FAIL->value);

        $passingScore = null;
        if ($resultMode === AssessmentResultMode::PASS_FAIL->value) {
            $passingScore = $assessment->passing_score !== null
                ? round((float) $assessment->passing_score, 2)
                : null;
        }

        return [
            'max_score' => $maxScore,
            'result_mode' => $resultMode,
            'passing_score' => $passingScore,
        ];
    }

    /**
     * Calculate score submission values using attempt snapshot attributes.
     *
     * @param mixed $attempt FinalExamAttempt, ModulePracticeAttempt, or snapshot array
     * @param float $earnedRawScore
     * @param bool $hasManualReview
     * @return array
     */
    public function calculateSubmission(mixed $attempt, float $earnedRawScore, bool $hasManualReview): array
    {
        if (is_array($attempt)) {
            $maxScore = round((float) ($attempt['max_score'] ?? 100), 2);
            $rawMode = $attempt['result_mode'] ?? 'pass_fail';
            $passingScore = isset($attempt['passing_score']) && $attempt['passing_score'] !== null
                ? round((float) $attempt['passing_score'], 2)
                : null;
        } else {
            $maxScore = round((float) ($attempt->max_score ?? 100), 2);
            $rawMode = $attempt->result_mode;
            $passingScore = $attempt->passing_score !== null
                ? round((float) $attempt->passing_score, 2)
                : null;
        }

        if ($maxScore <= 0) {
            throw new \InvalidArgumentException('Max score must be greater than zero for percentage calculation.');
        }

        $resultMode = $rawMode instanceof AssessmentResultMode
            ? $rawMode->value
            : (string) $rawMode;

        $earnedRawScore = round($earnedRawScore, 2);
        $percentageScore = round(($earnedRawScore / $maxScore) * 100, 2);

        $submittedAt = now();

        if ($hasManualReview) {
            return [
                'raw_score' => $earnedRawScore,
                'percentage_score' => $percentageScore,
                'is_passed' => null,
                'status' => 'waiting_review',
                'submitted_at' => $submittedAt,
                'graded_at' => null,
            ];
        }

        if ($resultMode === AssessmentResultMode::SCORE_ONLY->value) {
            return [
                'raw_score' => $earnedRawScore,
                'percentage_score' => $percentageScore,
                'is_passed' => null,
                'status' => 'submitted',
                'submitted_at' => $submittedAt,
                'graded_at' => $submittedAt,
            ];
        }

        // pass_fail mode
        $isPassed = ($passingScore !== null)
            ? ($earnedRawScore >= $passingScore)
            : true;

        return [
            'raw_score' => $earnedRawScore,
            'percentage_score' => $percentageScore,
            'is_passed' => $isPassed,
            'status' => $isPassed ? 'passed' : 'failed',
            'submitted_at' => $submittedAt,
            'graded_at' => $submittedAt,
        ];
    }

    /**
     * Calculate public Free Test submission result payload.
     *
     * @param FreeTest $freeTest
     * @param float $earnedRawScore
     * @return array
     */
    public function calculateFreeTestSubmission(FreeTest $freeTest, float $earnedRawScore): array
    {
        $snapshot = $this->createSnapshotPayload($freeTest);
        $maxScore = $snapshot['max_score'];
        $resultMode = $snapshot['result_mode'];
        $passingScore = $snapshot['passing_score'];

        $earnedRawScore = round($earnedRawScore, 2);
        $percentageScore = $maxScore > 0 ? round(($earnedRawScore / $maxScore) * 100, 2) : 0.00;

        $isPassed = null;
        if ($resultMode === AssessmentResultMode::PASS_FAIL->value && $passingScore !== null) {
            $isPassed = $earnedRawScore >= $passingScore;
        }

        $recommendation = $this->buildFreeTestRecommendation($resultMode, $isPassed, $earnedRawScore, $maxScore);

        return [
            'max_score' => $maxScore,
            'result_mode' => $resultMode,
            'passing_score' => $passingScore,
            'raw_score' => $earnedRawScore,
            'percentage_score' => $percentageScore,
            'is_passed' => $isPassed,
            'total_score' => (int) round($earnedRawScore),
            'recommendation' => $recommendation,
            'submitted_at' => now(),
        ];
    }

    private function buildFreeTestRecommendation(string $resultMode, ?bool $isPassed, float $earnedRawScore, float $maxScore): string
    {
        if ($resultMode === AssessmentResultMode::PASS_FAIL->value) {
            if ($isPassed === true) {
                return 'Great job! You passed this free test. You already have a strong foundation, and we recommend continuing with a structured program to sharpen your fluency, accuracy, and confidence.';
            }

            return 'We recommend starting from a foundational program to strengthen your English basics before moving to more advanced materials. Our team can help you choose the most suitable course based on your result.';
        }

        // Score Only Mode
        $pct = $maxScore > 0 ? ($earnedRawScore / $maxScore) * 100 : 0;
        if ($pct >= 70) {
            return 'Excellent performance! Your test score demonstrates solid proficiency. Explore our intermediate and advanced programs to keep building your skills.';
        }

        return 'Thank you for completing the free test! Based on your score, we recommend starting with our foundational modules to strengthen your core English skills.';
    }
}
