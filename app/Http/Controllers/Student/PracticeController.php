<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModulePractice;
use App\Models\ModulePracticeAttempt;
use App\Models\StudentCourseEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\StudentProgressService;
use App\Services\AdminNotificationService;

class PracticeController extends Controller
{
    public function show(
        StudentCourseEnrollment $enrollment,
        Module $module,
        ModulePractice $practice
    ): View|RedirectResponse {
        $this->authorizeAccess($enrollment, $module, $practice);

        if ($this->hasReachedMaxAttempts($practice)) {
            return redirect()
                ->route('student.module-material', [
                    'enrollment' => $enrollment,
                    'module' => $module,
                ])
                ->with('error', 'You have reached the maximum number of attempts for this practice.');
        }

        $practice->load([
            'module.courseLevel.courseProgram',
            'questions' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->with([
                        'options' => function ($optionQuery) {
                            $optionQuery
                                ->orderBy('sort_order')
                                ->orderBy('option_label');
                        },
                    ])
                    ->orderBy('sort_order')
                    ->orderBy('id');
            },
        ]);

        return view('pages.student.module-practice', [
            'enrollment' => $enrollment,
            'module' => $module,
            'practice' => $practice,
            'questions' => $practice->questions,
        ]);
    }

    public function submit(
        Request $request,
        StudentCourseEnrollment $enrollment,
        Module $module,
        ModulePractice $practice,
        StudentProgressService $progressService,
        AdminNotificationService $adminNotificationService
    ): RedirectResponse {
        $this->authorizeAccess($enrollment, $module, $practice);

        if ($this->hasReachedMaxAttempts($practice)) {
            return redirect()
                ->route('student.module-material', [
                    'enrollment' => $enrollment,
                    'module' => $module,
                ])
                ->with('error', 'You have reached the maximum number of attempts for this practice.');
        }

        $practice->load([
            'questions' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->with([
                        'options' => function ($optionQuery) {
                            $optionQuery
                                ->orderBy('sort_order')
                                ->orderBy('option_label');
                        },
                    ])
                    ->orderBy('sort_order')
                    ->orderBy('id');
            },
        ]);

        if ($practice->questions->isEmpty()) {
            return back()
                ->with('error', 'This practice does not have active questions yet.');
        }

        $answers = $request->input('answers', []);
        $errors = [];

        foreach ($practice->questions as $question) {
            $questionId = $question->id;

            if ($question->question_type === 'upload') {
                if (! $request->hasFile("uploads.$questionId")) {
                    $errors["uploads.$questionId"] = 'Please upload a file for this question.';
                }

                continue;
            }

            $answerValue = trim((string) ($answers[$questionId] ?? ''));

            if ($answerValue === '') {
                $errors["answers.$questionId"] = 'Please answer this question.';
            }
        }

        if (! empty($errors)) {
            return back()
                ->withErrors($errors)
                ->withInput();
        }

        $attemptNumber = ModulePracticeAttempt::query()
            ->where('student_id', auth()->id())
            ->where('module_practice_id', $practice->id)
            ->count() + 1;

        $attempt = ModulePracticeAttempt::create([
            'student_id' => auth()->id(),
            'module_practice_id' => $practice->id,
            'attempt_number' => $attemptNumber,
            'total_score' => 0,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        $earnedScore = 0;
        $maxScore = 0;
        $hasManualReview = false;

        foreach ($practice->questions as $question) {
            $questionScore = (float) $question->score;
            $maxScore += $questionScore;

            $answerPayload = [
                'module_practice_attempt_id' => $attempt->id,
                'module_practice_question_id' => $question->id,
                'selected_option_id' => null,
                'answer_text' => null,
                'uploaded_file' => null,
                'score' => 0,
                'feedback' => null,
                'is_correct' => null,
            ];

            if ($question->question_type === 'multiple_choice') {
                $selectedOptionId = (int) ($answers[$question->id] ?? 0);

                $selectedOption = $question->options
                    ->firstWhere('id', $selectedOptionId);

                if (! $selectedOption) {
                    $attempt->delete();

                    return back()
                        ->withErrors([
                            "answers.$question->id" => 'Selected option is invalid.',
                        ])
                        ->withInput();
                }

                $isCorrect = (bool) $selectedOption->is_correct;
                $score = $isCorrect ? $questionScore : 0;

                $earnedScore += $score;

                $answerPayload['selected_option_id'] = $selectedOption->id;
                $answerPayload['answer_text'] = $selectedOption->option_label;
                $answerPayload['score'] = $score;
                $answerPayload['is_correct'] = $isCorrect;
            } elseif ($question->question_type === 'upload') {
                $hasManualReview = true;

                $file = $request->file("uploads.$question->id");

                if ($file) {
                    $answerPayload['uploaded_file'] = $file->store('practice-answers', 'public');
                }
            } else {
                $hasManualReview = true;
                $answerPayload['answer_text'] = trim((string) ($answers[$question->id] ?? ''));
            }

            $attempt->answers()->create($answerPayload);
        }

        $percentageScore = $maxScore > 0
            ? round(($earnedScore / $maxScore) * 100, 2)
            : 0;

        $status = 'waiting_review';

        if (! $hasManualReview) {
            $status = $percentageScore >= (float) $practice->passing_grade
                ? 'passed'
                : 'failed';
        }

        $attempt->update([
            'total_score' => $percentageScore,
            'status' => $status,
            'submitted_at' => now(),
            'graded_at' => $hasManualReview ? null : now(),
        ]);

        if ($status === 'waiting_review') {
            $adminNotificationService->practiceWaitingReview($attempt->fresh());
        }

        $progressService->markModuleCompleted($enrollment, $module);

        return redirect()
            ->route('student.module-practice-result', [
                'enrollment' => $enrollment,
                'module' => $module,
                'attempt' => $attempt,
            ]);
    }


    public function result(
        StudentCourseEnrollment $enrollment,
        Module $module,
        ModulePracticeAttempt $attempt
    ): View {
        abort_unless($enrollment->student_id === auth()->id(), 403);
        abort_unless($module->course_level_id === $enrollment->course_level_id, 404);
        abort_unless($attempt->student_id === auth()->id(), 403);

        $attempt->load([
            'practice.module.courseLevel.courseProgram',
            'answers.question.options',
            'answers.selectedOption',
        ]);

        abort_unless($attempt->practice?->module_id === $module->id, 404);

        return view('pages.student.module-practice-result', [
            'enrollment' => $enrollment,
            'module' => $module,
            'practice' => $attempt->practice,
            'attempt' => $attempt,
            'answers' => $attempt->answers,
        ]);
    }

    private function authorizeAccess(
        StudentCourseEnrollment $enrollment,
        Module $module,
        ModulePractice $practice
    ): void {
        abort_unless($enrollment->student_id === auth()->id(), 403);

        abort_unless(
            in_array($enrollment->status, ['active', 'completed'], true),
            403
        );

        abort_unless($module->course_level_id === $enrollment->course_level_id, 404);
        abort_unless((bool) $module->is_active, 404);

        abort_unless($practice->module_id === $module->id, 404);
        abort_unless((bool) $practice->is_active, 404);
    }

    private function hasReachedMaxAttempts(ModulePractice $practice): bool
    {
        if (! $practice->max_attempts) {
            return false;
        }

        $attemptCount = ModulePracticeAttempt::query()
            ->where('student_id', auth()->id())
            ->where('module_practice_id', $practice->id)
            ->whereIn('status', ['submitted', 'passed', 'failed', 'waiting_review'])
            ->count();

        return $attemptCount >= (int) $practice->max_attempts;
    }
}
