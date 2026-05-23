<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FinalExam;
use App\Models\FinalExamAnswer;
use App\Models\FinalExamAttempt;
use App\Models\StudentCourseEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\CertificateService;
use App\Models\Certificate;
use App\Services\AdminNotificationService;

class FinalExamController extends Controller
{
    public function show(StudentCourseEnrollment $enrollment, FinalExam $finalExam): View|RedirectResponse
    {
        $this->authorizeAccess($enrollment, $finalExam);

        if (! $this->isFinalExamUnlocked($enrollment)) {
            return redirect()
                ->route('student.learning-path', $enrollment)
                ->with('error', 'Please complete all modules before starting the final exam.');
        }

        if ($this->hasReachedMaxAttempts($finalExam)) {
            return redirect()
                ->route('student.learning-path', $enrollment)
                ->with('error', 'You have reached the maximum number of attempts for this final exam.');
        }

        $finalExam->load([
            'courseLevel.courseProgram',
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

        return view('pages.student.final-exam', [
            'enrollment' => $enrollment,
            'courseLevel' => $enrollment->courseLevel,
            'finalExam' => $finalExam,
            'questions' => $finalExam->questions,
        ]);
    }

    public function submit(
        Request $request,
        StudentCourseEnrollment $enrollment,
        FinalExam $finalExam,
        CertificateService $certificateService,
        AdminNotificationService $adminNotificationService
    ): RedirectResponse {
        $this->authorizeAccess($enrollment, $finalExam);

        if (! $this->isFinalExamUnlocked($enrollment)) {
            return redirect()
                ->route('student.learning-path', $enrollment)
                ->with('error', 'Please complete all modules before submitting the final exam.');
        }

        if ($this->hasReachedMaxAttempts($finalExam)) {
            return redirect()
                ->route('student.learning-path', $enrollment)
                ->with('error', 'You have reached the maximum number of attempts for this final exam.');
        }

        $finalExam->load([
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

        if ($finalExam->questions->isEmpty()) {
            return back()
                ->with('error', 'This final exam does not have active questions yet.');
        }

        $answers = $request->input('answers', []);
        $errors = [];

        foreach ($finalExam->questions as $question) {
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

        $attemptNumber = FinalExamAttempt::query()
            ->where('student_id', auth()->id())
            ->where('final_exam_id', $finalExam->id)
            ->count() + 1;

        $attempt = FinalExamAttempt::create([
            'student_id' => auth()->id(),
            'final_exam_id' => $finalExam->id,
            'attempt_number' => $attemptNumber,
            'total_score' => 0,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        $earnedScore = 0;
        $maxScore = 0;
        $hasManualReview = false;

        foreach ($finalExam->questions as $question) {
            $questionScore = (float) $question->score;
            $maxScore += $questionScore;

            $answerPayload = [
                'final_exam_attempt_id' => $attempt->id,
                'final_exam_question_id' => $question->id,
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
                    $answerPayload['uploaded_file'] = $file->store('final-exam-answers', 'public');
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
            $status = $percentageScore >= (float) $finalExam->passing_grade
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
            $adminNotificationService->finalExamWaitingReview($attempt->fresh());
        }

        if ($status === 'passed') {
            $certificateService->createLockedCertificateFromAttempt($attempt->fresh());
        }
        return redirect()
            ->route('student.final-exam-result', [
                'enrollment' => $enrollment,
                'attempt' => $attempt,
            ]);
    }

    public function result(StudentCourseEnrollment $enrollment, FinalExamAttempt $attempt): View
    {
        abort_unless($enrollment->student_id === auth()->id(), 403);
        abort_unless($attempt->student_id === auth()->id(), 403);

        $attempt->load([
            'finalExam.courseLevel.courseProgram',
            'answers.question.options',
            'answers.selectedOption',
        ]);

        abort_unless($attempt->finalExam?->course_level_id === $enrollment->course_level_id, 404);

        $finalExam = $attempt->finalExam;

        $certificate = null;

        if ($attempt->status === 'passed') {
            $certificate = Certificate::query()
                ->where('student_id', auth()->id())
                ->where('enrollment_id', $enrollment->id)
                ->where('course_level_id', $enrollment->course_level_id)
                ->whereIn('status', ['locked', 'issued'])
                ->latest()
                ->first();
        }

        $finalExamAttemptCount = FinalExamAttempt::query()
            ->where('student_id', auth()->id())
            ->where('final_exam_id', $finalExam->id)
            ->whereIn('status', ['passed', 'failed', 'waiting_review'])
            ->count();

        $canRetakeFinalExam = $attempt->status === 'failed'
            && (
                ! $finalExam->max_attempts
                || $finalExamAttemptCount < (int) $finalExam->max_attempts
            );

        return view('pages.student.final-exam-result', [
            'enrollment' => $enrollment,
            'courseLevel' => $enrollment->courseLevel,
            'finalExam' => $finalExam,
            'attempt' => $attempt,
            'answers' => $attempt->answers,
            'certificate' => $certificate,
            'finalExamAttemptCount' => $finalExamAttemptCount,
            'canRetakeFinalExam' => $canRetakeFinalExam,
        ]);
    }

    private function authorizeAccess(StudentCourseEnrollment $enrollment, FinalExam $finalExam): void
    {
        abort_unless($enrollment->student_id === auth()->id(), 403);

        abort_unless(
            in_array($enrollment->status, ['active', 'completed'], true),
            403
        );

        $enrollment->loadMissing('courseLevel');

        abort_unless($enrollment->courseLevel, 404);
        abort_unless($finalExam->course_level_id === $enrollment->course_level_id, 404);
        abort_unless((bool) $finalExam->is_active, 404);
    }

    private function isFinalExamUnlocked(StudentCourseEnrollment $enrollment): bool
    {
        return (float) $enrollment->progress_percentage >= 100;
    }

    private function hasReachedMaxAttempts(FinalExam $finalExam): bool
    {
        if (! $finalExam->max_attempts) {
            return false;
        }

        $attemptCount = FinalExamAttempt::query()
            ->where('student_id', auth()->id())
            ->where('final_exam_id', $finalExam->id)
            ->whereIn('status', ['passed', 'failed', 'waiting_review'])
            ->count();

        return $attemptCount >= (int) $finalExam->max_attempts;
    }
}
