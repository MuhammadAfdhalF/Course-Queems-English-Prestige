@extends('layouts.learning')

@section('content')
@php
$resultMode = $attempt->result_mode instanceof \App\Enums\AssessmentResultMode ? $attempt->result_mode->value : (string) ($attempt->result_mode ?? 'pass_fail');

$statusLabel = match ($attempt->status) {
    'passed' => 'Passed',
    'failed' => 'Failed',
    'waiting_review' => 'Waiting for Review',
    default => 'Submitted',
};

$statusClass = match ($attempt->status) {
    'passed' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100',
    'failed' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-100',
    'waiting_review' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-100',
    default => 'bg-blue-50 text-blue-700 ring-1 ring-blue-100',
};

$statusDescription = match ($attempt->status) {
    'passed' => 'Congratulations! You passed the final exam. Your course completion record has been updated.',
    'failed' => 'You submitted the final exam, but your score did not reach the required passing score.',
    'waiting_review' => 'Some answers require manual review by admin before the final result is confirmed.',
    default => 'Your final exam submission has been recorded successfully.',
};
@endphp

<section class="mx-auto max-w-5xl space-y-6">
    <div class="reveal">
        <a href="{{ route('student.learning-path', $enrollment) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Learning Path
        </a>
    </div>

    <div class="reveal reveal-delay-1 overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
        <div class="px-6 py-8 text-center lg:px-10">
            <span class="inline-flex rounded-full px-4 py-2 text-xs font-extrabold uppercase tracking-[0.16em] {{ $statusClass }}">
                {{ $statusLabel }}
            </span>

            <h1 class="mt-5 text-4xl font-extrabold leading-tight text-slate-900">
                Final Exam Result
            </h1>

            <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600">
                {{ $statusDescription }}
            </p>

            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Score Obtained
                    </p>
                    <p class="mt-2 text-2xl font-extrabold text-[var(--color-brand-blue)]">
                        {{ number_format((float) ($attempt->raw_score ?? 0), 2) }} / {{ number_format((float) ($attempt->max_score ?? 100), 2) }}
                    </p>
                    <p class="text-xs font-semibold text-slate-500 mt-1">
                        ({{ number_format((float) ($attempt->percentage_score ?? 0), 2) }}%)
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Passing Criteria
                    </p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">
                        @if ($resultMode === 'pass_fail')
                            {{ number_format((float) ($attempt->passing_score ?? 0), 2) }} pts
                        @else
                            Score Only
                        @endif
                    </p>
                    <p class="text-xs font-semibold text-slate-500 mt-1">
                        Mode: {{ strtoupper($resultMode) }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Attempt
                    </p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">
                        #{{ $attempt->attempt_number }}
                    </p>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200 bg-slate-50 px-6 py-5 lg:px-10">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-center">
                <a
                    href="{{ route('student.learning-path', $enrollment) }}"
                    class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                    Back to Learning Path
                </a>

                <a
                    href="{{ route('student.my-courses') }}"
                    class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white transition hover:opacity-95">
                    Back to My Courses
                </a>
            </div>
        </div>
    </div>

    <div class="reveal reveal-delay-2 rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
        @if ($attempt->status === 'passed')
        @if ($certificate?->status === 'issued')
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-500">
                    Certificate Issued
                </p>

                <h2 class="mt-2 text-2xl font-extrabold text-slate-900">
                    Your certificate is ready.
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    You have passed the final exam and your certificate has been issued.
                </p>
            </div>

            <a
                href="{{ route('student.certificates.show', $certificate) }}"
                class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-md transition hover:opacity-95">
                View Certificate
            </a>
        </div>
        @elseif ($certificate?->status === 'locked')
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[var(--color-brand-gold)]">
                    Certificate Locked
                </p>

                <h2 class="mt-2 text-2xl font-extrabold text-slate-900">
                    Submit your testimonial to unlock your certificate.
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Your final exam is passed. One more step: submit your learning testimonial and your certificate will be issued immediately.
                </p>
            </div>

            <a
                href="{{ route('student.testimoni') }}"
                class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-md transition hover:opacity-95">
                Write Testimonial
            </a>
        </div>
        @else
        <div class="rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4">
            <h2 class="text-lg font-extrabold text-amber-800">
                Certificate is being prepared.
            </h2>

            <p class="mt-2 text-sm leading-6 text-amber-700">
                You passed the final exam, but your certificate record is not available yet. Please contact admin if this message does not change.
            </p>
        </div>
        @endif
        @elseif ($attempt->status === 'waiting_review')
        <div class="rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4">
            <h2 class="text-lg font-extrabold text-amber-800">
                Your final exam is waiting for admin review.
            </h2>

            <p class="mt-2 text-sm leading-6 text-amber-700">
                Some answers need manual grading. Your certificate will be available after the final exam result is confirmed as passed.
            </p>
        </div>
        @elseif ($attempt->status === 'failed')
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-rose-500">
                    Final Exam Not Passed
                </p>

                <h2 class="mt-2 text-2xl font-extrabold text-slate-900">
                    You can try again if attempts are still available.
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Attempt used: {{ $finalExamAttemptCount }}{{ $finalExam->max_attempts ? ' / ' . $finalExam->max_attempts : '' }}.
                    Review your answers before retaking the final exam.
                </p>
            </div>

            @if ($canRetakeFinalExam)
            <a
                href="{{ route('student.final-exam', ['enrollment' => $enrollment, 'finalExam' => $finalExam]) }}"
                class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-md transition hover:opacity-95">
                Retake Final Exam
            </a>
            @else
            <div class="inline-flex h-12 items-center justify-center rounded-xl bg-slate-100 px-6 text-sm font-bold text-slate-400">
                No Attempts Remaining
            </div>
            @endif
        </div>
        @endif
    </div>
    <div class="reveal reveal-delay-2">
        <div class="mb-4">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">
                Answer Review
            </p>

            <h2 class="mt-2 text-3xl font-extrabold text-slate-900">
                Your Final Exam Answers
            </h2>
        </div>

        <div class="space-y-5">
            @forelse ($answers as $index => $answer)
            @php
            $question = $answer->question;

            $typeLabel = match ($question?->question_type) {
            'multiple_choice' => 'Multiple Choice',
            'short_answer' => 'Short Answer',
            'essay' => 'Essay',
            'upload' => 'Upload',
            default => 'Question',
            };

            $answerStatusLabel = match (true) {
            $answer->is_correct === true => 'Correct',
            $answer->is_correct === false => 'Incorrect',
            $attempt->status === 'waiting_review' && in_array($question?->question_type, ['short_answer', 'essay', 'upload'], true) => 'Waiting Review',
            default => 'Reviewed',
            };

            $answerStatusClass = match (true) {
            $answer->is_correct === true => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100',
            $answer->is_correct === false => 'bg-rose-50 text-rose-700 ring-1 ring-rose-100',
            $attempt->status === 'waiting_review' && in_array($question?->question_type, ['short_answer', 'essay', 'upload'], true) => 'bg-amber-50 text-amber-700 ring-1 ring-amber-100',
            default => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
            };

            $correctOption = $question?->options?->firstWhere('is_correct', true);
            @endphp

            <article class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                                Question {{ $index + 1 }} · {{ $typeLabel }}
                            </p>

                            <p class="mt-1 text-sm font-bold text-slate-700">
                                Score:
                                <span class="text-slate-900">
                                    {{ number_format((float) $answer->score, 2) }}
                                </span>
                                /
                                <span class="text-slate-900">
                                    {{ number_format((float) ($question?->score ?? 0), 2) }}
                                </span>
                            </p>
                        </div>

                        <span class="inline-flex w-max rounded-full px-3 py-1 text-xs font-extrabold uppercase tracking-[0.12em] {{ $answerStatusClass }}">
                            {{ $answerStatusLabel }}
                        </span>
                    </div>
                </div>

                <div class="space-y-5 px-6 py-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                            Question
                        </p>

                        <div class="rich-text-content mt-3 max-w-none">
                            {!! $question?->question !!}
                        </div>
                    </div>

                    @if ($question?->question_type === 'multiple_choice')
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                            Your Answer
                        </p>

                        <p class="mt-2 text-base font-bold leading-7 text-slate-900">
                            {{ $answer->selectedOption?->option_label }}.
                            {{ $answer->selectedOption?->option_text }}
                        </p>

                        @if ($correctOption)
                        <div class="mt-4 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-emerald-600">
                                Correct Answer
                            </p>

                            <p class="mt-1 text-sm font-bold leading-6 text-emerald-800">
                                {{ $correctOption->option_label }}.
                                {{ $correctOption->option_text }}
                            </p>
                        </div>
                        @endif
                    </div>
                    @elseif ($question?->question_type === 'upload')
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                            Uploaded File
                        </p>

                        @if ($answer->uploaded_file)
                        <a
                            href="{{ asset('storage/' . $answer->uploaded_file) }}"
                            target="_blank"
                            class="mt-3 inline-flex h-10 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-sm font-bold text-white transition hover:opacity-95">
                            Open Uploaded File
                        </a>
                        @else
                        <p class="mt-2 text-sm font-semibold text-slate-500">
                            No uploaded file.
                        </p>
                        @endif
                    </div>
                    @else
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                            Your Answer
                        </p>

                        <p class="mt-3 whitespace-pre-line text-base leading-7 text-slate-800">
                            {{ $answer->answer_text ?: '-' }}
                        </p>
                    </div>
                    @endif

                    @if ($answer->feedback)
                    <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-500">
                            Admin Feedback
                        </p>

                        <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-700">
                            {{ $answer->feedback }}
                        </p>
                    </div>
                    @elseif ($attempt->status === 'waiting_review' && in_array($question?->question_type, ['short_answer', 'essay', 'upload'], true))
                    <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5">
                        <p class="text-sm font-semibold leading-6 text-amber-700">
                            This answer is waiting for admin review. Feedback will appear here after review.
                        </p>
                    </div>
                    @endif

                    @if ($question?->explanation)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                            Explanation
                        </p>

                        <div class="rich-text-content mt-3 max-w-none">
                            {!! $question->explanation !!}
                        </div>
                    </div>
                    @endif
                </div>
            </article>
            @empty
            <div class="rounded-[22px] border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm">
                <h3 class="text-2xl font-extrabold text-slate-900">
                    No Answers Found
                </h3>

                <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-500">
                    This attempt does not have answer records yet.
                </p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection