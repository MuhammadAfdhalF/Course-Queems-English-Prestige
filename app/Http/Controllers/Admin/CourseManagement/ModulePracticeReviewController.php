<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\ModulePractice;
use App\Models\ModulePracticeAnswer;
use App\Models\ModulePracticeAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\AssessmentScoringService;
use App\Services\StudentNotificationService;
use App\Services\StudentProgressService;

class ModulePracticeReviewController extends Controller
{
    public function __construct(
        protected AssessmentScoringService $scoringService
    ) {}

    public function index(ModulePractice $modulePractice): View
    {
        $modulePractice->load('module.courseLevel.courseProgram');

        $attempts = $modulePractice->attempts()
            ->with('student')
            ->latest('submitted_at')
            ->latest()
            ->get();

        return view('pages.admin.course-management.practice-reviews.index', [
            'modulePractice' => $modulePractice,
            'attempts' => $attempts,
        ]);
    }

    public function show(ModulePracticeAttempt $modulePracticeAttempt): View
    {
        $modulePracticeAttempt->load([
            'student',
            'practice.module.courseLevel.courseProgram',
            'answers.question.options',
            'answers.selectedOption',
        ]);

        return view('pages.admin.course-management.practice-reviews.show', [
            'attempt' => $modulePracticeAttempt,
            'practice' => $modulePracticeAttempt->practice,
            'student' => $modulePracticeAttempt->student,
            'answers' => $modulePracticeAttempt->answers,
        ]);
    }

    public function update(
        Request $request,
        ModulePracticeAttempt $modulePracticeAttempt,
        StudentNotificationService $studentNotificationService,
        StudentProgressService $progressService
    ): RedirectResponse {
        if ($modulePracticeAttempt->status !== 'waiting_review' || $modulePracticeAttempt->graded_at !== null) {
            return redirect()
                ->route('admin.course-management.practice-reviews.show', $modulePracticeAttempt)
                ->with('error', 'This attempt has already been finalized or is not waiting for review.');
        }

        $validated = $request->validate([
            'answers' => ['nullable', 'array'],
        ]);

        $this->scoringService->finalizeAttempt($modulePracticeAttempt, $validated['answers'] ?? []);

        $attempt = $modulePracticeAttempt->fresh();

        $studentNotificationService->practiceReviewed($attempt);

        $modulePracticeAttempt->loadMissing('practice.module');
        $module = $modulePracticeAttempt->practice?->module;
        if ($module) {
            $enrollment = \App\Models\StudentCourseEnrollment::query()
                ->where('student_id', $attempt->student_id)
                ->where('course_level_id', $module->course_level_id)
                ->whereIn('status', ['active', 'completed'])
                ->latest('enrolled_at')
                ->latest()
                ->first();

            if ($enrollment) {
                $progressService->evaluateAndSyncModuleCompletion($enrollment, $module);
            }
        }

        return redirect()
            ->route('admin.course-management.practice-reviews.show', $attempt)
            ->with('success', 'Practice attempt has been reviewed successfully.');
    }
}
