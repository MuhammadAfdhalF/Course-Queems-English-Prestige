@extends('layouts.learning')

@section('content')
<section class="mx-auto max-w-4xl space-y-6">
    <div class="reveal">
        <a href="{{ route('student.module-material', ['enrollment' => $enrollment, 'module' => $module]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Module Material
        </a>
    </div>

    <div class="reveal reveal-delay-1 overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
        <div class="border-l-4 border-[var(--color-brand-gold)] px-6 py-6">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[var(--color-brand-gold)]">
                Module Practice
            </p>

            <h1 class="mt-3 text-3xl font-extrabold leading-tight text-slate-900 lg:text-4xl">
                {{ $practice->title }}
            </h1>

            @if ($practice->description)
            <div class="rich-text-content mt-4 text-base leading-7 text-slate-600">
                {!! $practice->description !!}
            </div>
            @endif

            <div class="mt-5 flex flex-wrap gap-2">
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                    Passing Grade: {{ $practice->passing_grade }}%
                </span>

                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                    {{ $questions->count() }} Questions
                </span>

                @if ($practice->max_attempts)
                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                    Max Attempts: {{ $practice->max_attempts }}
                </span>
                @endif
            </div>
        </div>
    </div>

    @if ($errors->any())
    <div class="reveal rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-600">
        Please complete all required answers before submitting.
    </div>
    @endif

    <form
        action="{{ route('student.module-practice.submit', ['enrollment' => $enrollment, 'module' => $module, 'practice' => $practice]) }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-5">
        @csrf

        @forelse ($questions as $index => $question)
        @php
        $delayClass = match ($index) {
        1 => 'reveal-delay-1',
        2 => 'reveal-delay-2',
        3 => 'reveal-delay-3',
        4 => 'reveal-delay-4',
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
            <x-student.practice-question-card
                :question-id="$question->id"
                :number="'Question ' . ($index + 1)"
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
                This practice does not have active questions yet.
            </p>
        </div>
        @endforelse

        @if ($questions->count() > 0)
        <div class="reveal reveal-delay-2 flex flex-col gap-4 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('student.module-material', ['enrollment' => $enrollment, 'module' => $module]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Module
            </a>

            <button
                type="submit"
                class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-8 text-base font-bold text-white shadow-md transition hover:opacity-95">
                Submit Practice
            </button>
        </div>
        @endif
    </form>
</section>
@endsection