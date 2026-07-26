@extends('layouts.learning')

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <div class="reveal">
        <a href="{{ route('student.learning-path', $enrollment) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Learning Path
        </a>
    </div>

    <div class="reveal reveal-delay-1">
        <div class="flex flex-wrap items-center gap-4">
            <h1 class="text-4xl font-bold leading-tight text-slate-900 lg:text-5xl">
                {{ $finalExam->title }}
            </h1>

            <span class="inline-flex items-center rounded-full bg-[#FFD400] px-4 py-1.5 text-sm font-bold uppercase tracking-[0.08em] text-slate-900">
                Open
            </span>
        </div>

        <p class="mt-4 text-lg text-slate-500 lg:text-2xl">
            Please read the instructions carefully before submitting your final assessment.
        </p>

        <div class="mt-8 rounded-[24px] border border-slate-200 bg-white px-6 py-6 shadow-sm lg:px-8">
            <div class="grid gap-6 md:grid-cols-3">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-[var(--color-brand-blue)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <rect x="5" y="3" width="14" height="18" rx="2" stroke-width="1.8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8M8 11h8M8 15h5" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Total Questions</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ $questions->count() }} Questions</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-[var(--color-brand-blue)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 7v5l3 3" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Estimated Time</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">30 Minutes</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-[var(--color-brand-blue)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.147 3.532a1 1 0 00.95.69h3.713c.969 0 1.371 1.24.588 1.81l-3.004 2.183a1 1 0 00-.364 1.118l1.147 3.532c.3.922-.755 1.688-1.54 1.118l-3.004-2.182a1 1 0 00-1.176 0l-3.004 2.182c-.784.57-1.838-.196-1.539-1.118l1.147-3.532a1 1 0 00-.364-1.118L2.98 8.96c-.783-.57-.38-1.81.588-1.81h3.713a1 1 0 00.95-.69l1.147-3.532z" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Passing Criteria</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">
                            @if ($finalExam->result_mode === \App\Enums\AssessmentResultMode::PASS_FAIL || $finalExam->result_mode === 'pass_fail')
                                {{ number_format((float) $finalExam->passing_score, 2) }} pts
                            @else
                                Score Only
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            @if ($finalExam->description)
            <div class="rich-text-content mt-6 border-t border-slate-200 pt-6">
                {!! $finalExam->description !!}
            </div>
            @endif
        </div>
    </div>

    @if ($errors->any())
    <div class="reveal rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-600">
        Please complete all required answers before submitting.
    </div>
    @endif

    <form
        action="{{ route('student.final-exam.submit', ['enrollment' => $enrollment, 'finalExam' => $finalExam]) }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-5">
        @csrf

        @forelse ($questions as $index => $question)
        @php
        $delayClass = match ($index) {
        1 => 'reveal-delay-1',
        2 => 'reveal-delay-2',
        default => '',
        };

        $typeLabel = match ($question->question_type) {
        'multiple_choice' => 'Multiple Choice',
        'short_answer' => 'Short Answer',
        'essay' => 'Essay',
        'upload' => 'Upload',
        default => 'Question',
        };
        @endphp

        <div class="reveal {{ $delayClass }}">
            <x-student.final-exam-question-card
                :question-id="$question->id"
                :number="'Question ' . ($index + 1) . ' of ' . $questions->count()"
                :type="$question->question_type"
                :type-label="$typeLabel"
                :question="$question->question"
                :options="$question->options"
                placeholder="Type your answer here..." />
        </div>
        @empty
        <div class="rounded-[22px] border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm">
            <h2 class="text-2xl font-extrabold text-slate-900">
                No Questions Yet
            </h2>

            <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-500">
                This final exam does not have active questions yet.
            </p>
        </div>
        @endforelse

        @if ($questions->count() > 0)
        <div class="reveal reveal-delay-2 flex flex-col gap-4 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
            <a
                href="{{ route('student.learning-path', $enrollment) }}"
                class="inline-flex h-12 items-center justify-center rounded-xl px-6 text-base font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
                Back to Learning Path
            </a>

            <button
                type="submit"
                class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-8 text-base font-bold text-white shadow-md transition hover:opacity-95">
                Submit Final Exam
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M13 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        @endif
    </form>
</section>
@endsection