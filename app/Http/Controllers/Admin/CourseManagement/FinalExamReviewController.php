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
use App\Services\AssessmentScoringService;
use App\Services\CertificateService;
use App\Services\StudentNotificationService;

class FinalExamReviewController extends Controller
{
    public function __construct(
        protected AssessmentScoringService $scoringService
    ) {}

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
        if ($finalExamAttempt->status !== 'waiting_review' || $finalExamAttempt->graded_at !== null) {
            return redirect()
                ->route('admin.course-management.final-exam-reviews.show', $finalExamAttempt)
                ->with('error', 'This attempt has already been finalized or is not waiting for review.');
        }

        $validated = $request->validate([
            'answers' => ['nullable', 'array'],
        ]);

        $this->scoringService->finalizeAttempt($finalExamAttempt, $validated['answers'] ?? []);

        $attempt = $finalExamAttempt->fresh();

        if ($attempt->status === 'passed') {
            $courseLevelId = $attempt->finalExam?->course_level_id;
            if ($courseLevelId) {
                $enrollment = StudentCourseEnrollment::query()
                    ->where('student_id', $attempt->student_id)
                    ->where('course_level_id', $courseLevelId)
                    ->whereIn('status', ['active', 'completed'])
                    ->latest('enrolled_at')
                    ->latest()
                    ->first();

                if ($enrollment) {
                    $certificateService->evaluateAndCreateForEnrollment($enrollment);
                }
            }
        }

        $studentNotificationService->finalExamReviewed($attempt);

        return redirect()
            ->route('admin.course-management.final-exam-reviews.show', $attempt)
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
