<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\ModulePractice;
use App\Models\ModulePracticeAnswer;
use App\Models\ModulePracticeAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\StudentNotificationService;

class ModulePracticeReviewController extends Controller
{
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
        StudentNotificationService $studentNotificationService
    ): RedirectResponse {
        $modulePracticeAttempt->load([
            'practice.questions',
            'answers.question',
        ]);

        $wasWaitingReview = $modulePracticeAttempt->status === 'waiting_review';

        $validated = $request->validate([
            'answers' => ['nullable', 'array'],
            'answers.*.score' => ['nullable', 'numeric', 'min:0'],
            'answers.*.feedback' => ['nullable', 'string'],
        ]);

        $inputAnswers = $validated['answers'] ?? [];

        foreach ($modulePracticeAttempt->answers as $answer) {
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

        $modulePracticeAttempt->refresh()->load([
            'practice.questions',
            'answers.question',
        ]);

        $earnedScore = (float) $modulePracticeAttempt->answers->sum(function (ModulePracticeAnswer $answer) {
            return (float) $answer->score;
        });

        $maxScore = (float) $modulePracticeAttempt->practice->questions->sum(function ($question) {
            return (float) $question->score;
        });

        $percentageScore = $maxScore > 0
            ? round(($earnedScore / $maxScore) * 100, 2)
            : 0;

        $status = $percentageScore >= (float) $modulePracticeAttempt->practice->passing_grade
            ? 'passed'
            : 'failed';

        $modulePracticeAttempt->update([
            'total_score' => $percentageScore,
            'status' => $status,
            'graded_at' => now(),
        ]);
        if ($wasWaitingReview) {
            $studentNotificationService->practiceReviewed($modulePracticeAttempt->fresh());
        }
        return redirect()
            ->route('admin.course-management.practice-reviews.show', $modulePracticeAttempt)
            ->with('success', 'Practice attempt has been reviewed successfully.');
    }
}
