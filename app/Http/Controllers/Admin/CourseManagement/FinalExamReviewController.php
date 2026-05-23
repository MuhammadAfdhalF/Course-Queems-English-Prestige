<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\FinalExam;
use App\Models\FinalExamAnswer;
use App\Models\FinalExamAttempt;
use App\Models\StudentCourseEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\CertificateService;

use App\Services\StudentNotificationService;

class FinalExamReviewController extends Controller
{
    public function index(FinalExam $finalExam): View
    {
        $finalExam->load('courseLevel.courseProgram');

        $attempts = $finalExam->attempts()
            ->with('student')
            ->latest('submitted_at')
            ->latest()
            ->get();

        return view('pages.admin.course-management.final-exam-reviews.index', [
            'finalExam' => $finalExam,
            'attempts' => $attempts,
        ]);
    }

    public function show(FinalExamAttempt $finalExamAttempt): View
    {
        $finalExamAttempt->load([
            'student',
            'finalExam.courseLevel.courseProgram',
            'answers.question.options',
            'answers.selectedOption',
        ]);

        $answers = $finalExamAttempt->answers
            ->sortBy(fn(FinalExamAnswer $answer) => $answer->question?->sort_order ?? 999999)
            ->values();

        return view('pages.admin.course-management.final-exam-reviews.show', [
            'attempt' => $finalExamAttempt,
            'finalExam' => $finalExamAttempt->finalExam,
            'student' => $finalExamAttempt->student,
            'answers' => $answers,
        ]);
    }

    public function update(
        Request $request,
        FinalExamAttempt $finalExamAttempt,
        CertificateService $certificateService,
        StudentNotificationService $studentNotificationService
    ): RedirectResponse {
        $finalExamAttempt->load([
            'finalExam.questions',
            'answers.question',
        ]);
        $wasWaitingReview = $finalExamAttempt->status === 'waiting_review';
        $validated = $request->validate([
            'answers' => ['nullable', 'array'],
            'answers.*.score' => ['nullable', 'numeric', 'min:0'],
            'answers.*.feedback' => ['nullable', 'string'],
        ]);

        $inputAnswers = $validated['answers'] ?? [];

        foreach ($finalExamAttempt->answers as $answer) {
            if (! in_array($answer->question?->question_type, ['short_answer', 'essay', 'upload'], true)) {
                continue;
            }

            $answerInput = $inputAnswers[$answer->id] ?? [];

            $maxQuestionScore = (float) ($answer->question?->score ?? 0);
            $score = (float) ($answerInput['score'] ?? 0);

            if ($score > $maxQuestionScore) {
                return back()
                    ->withErrors([
                        'answers.' . $answer->id . '.score' => 'Score cannot be greater than the question max score.',
                    ])
                    ->withInput();
            }

            $answer->update([
                'score' => $score,
                'feedback' => $answerInput['feedback'] ?? null,
                'is_correct' => $score >= $maxQuestionScore && $maxQuestionScore > 0,
            ]);
        }

        $finalExamAttempt->refresh()->load([
            'finalExam.questions',
            'answers.question',
        ]);

        $earnedScore = (float) $finalExamAttempt->answers->sum(function (FinalExamAnswer $answer) {
            return (float) $answer->score;
        });

        $maxScore = (float) $finalExamAttempt->finalExam->questions->sum(function ($question) {
            return (float) $question->score;
        });

        $percentageScore = $maxScore > 0
            ? round(($earnedScore / $maxScore) * 100, 2)
            : 0;

        $status = $percentageScore >= (float) $finalExamAttempt->finalExam->passing_grade
            ? 'passed'
            : 'failed';

        $finalExamAttempt->update([
            'total_score' => $percentageScore,
            'status' => $status,
            'graded_at' => now(),
        ]);

        if ($status === 'passed') {
            $certificateService->createLockedCertificateFromAttempt($finalExamAttempt->fresh());
        }

        if ($wasWaitingReview) {
            $studentNotificationService->finalExamReviewed($finalExamAttempt->fresh());
        }

        return redirect()
            ->route('admin.course-management.final-exam-reviews.show', $finalExamAttempt)
            ->with('success', 'Final exam attempt has been reviewed successfully.');
    }

    private function markEnrollmentCompleted(FinalExamAttempt $attempt): void
    {
        $attempt->loadMissing('finalExam');

        $courseLevelId = $attempt->finalExam?->course_level_id;

        if (! $courseLevelId) {
            return;
        }

        $enrollment = StudentCourseEnrollment::query()
            ->where('student_id', $attempt->student_id)
            ->where('course_level_id', $courseLevelId)
            ->whereIn('status', ['active', 'completed'])
            ->latest('enrolled_at')
            ->latest()
            ->first();

        if (! $enrollment) {
            return;
        }

        $enrollment->update([
            'status' => 'completed',
            'progress_percentage' => 100,
            'completed_at' => $enrollment->completed_at ?? now(),
        ]);
    }
}
