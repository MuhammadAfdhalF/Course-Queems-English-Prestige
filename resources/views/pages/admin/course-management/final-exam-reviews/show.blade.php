@extends('layouts.admin', [
'pageTitle' => 'Review Final Exam Attempt',
'pageSubtitle' => $finalExam->title,
])

@section('content')
@php
$statusClasses = match ($attempt->status) {
'passed' => 'bg-emerald-50 text-emerald-700',
'failed' => 'bg-rose-50 text-rose-700',
'waiting_review' => 'bg-amber-50 text-amber-700',
'in_progress' => 'bg-slate-100 text-slate-500',
default => 'bg-slate-100 text-slate-600',
};

$statusLabel = match ($attempt->status) {
'passed' => 'Passed',
'failed' => 'Failed',
'waiting_review' => 'Waiting Review',
'in_progress' => 'In Progress',
default => ucfirst(str_replace('_', ' ', $attempt->status)),
};
@endphp

<section class="mx-auto max-w-6xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.final-exam-reviews.index', $finalExam)"
        back-label="Back to Attempts" />

    <x-admin.flash-message />

    @if ($errors->any())
    <div class="rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-600">
        Please check the scores. Score cannot be greater than each question max score.
    </div>
    @endif

    <x-admin.table-card class="p-6">
        <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-start">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Student Final Exam Attempt
                </p>

                <h2 class="mt-2 text-2xl font-bold text-slate-900">
                    {{ $student?->name ?? 'Unknown Student' }}
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    {{ $student?->email ?? '-' }}
                </p>

                <p class="mt-4 text-sm leading-6 text-slate-600">
                    {{ $finalExam->courseLevel->courseProgram->name }}
                    —
                    {{ $finalExam->courseLevel->name }}
                </p>
            </div>

            <div class="grid gap-3 sm:grid-cols-3 lg:min-w-[420px]">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                        Attempt
                    </p>
                    <p class="mt-2 text-xl font-bold text-slate-900">
                        #{{ $attempt->attempt_number }}
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                        Score
                    </p>
                    <p class="mt-2 text-xl font-bold text-slate-900">
                        {{ number_format((float) $attempt->total_score, 2) }}%
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                        Status
                    </p>
                    <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses }}">
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>
        </div>
    </x-admin.table-card>

    <form
        action="{{ route('admin.course-management.final-exam-reviews.update', $attempt) }}"
        method="POST"
        class="space-y-5">
        @csrf
        @method('PUT')

        @foreach ($answers as $index => $answer)
        @php
        $question = $answer->question;
        $isManual = in_array($question?->question_type, ['short_answer', 'essay', 'upload'], true);

        $typeLabel = match ($question?->question_type) {
        'multiple_choice' => 'Multiple Choice',
        'short_answer' => 'Short Answer',
        'essay' => 'Essay',
        'upload' => 'Upload',
        default => 'Question',
        };

        $answerStatusClass = $answer->is_correct === true
        ? 'bg-emerald-50 text-emerald-700'
        : ($answer->is_correct === false ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700');

        $answerStatusLabel = $answer->is_correct === true
        ? 'Correct'
        : ($answer->is_correct === false ? 'Incorrect' : 'Manual Review');
        @endphp

        <x-admin.table-card class="overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                            Question {{ $index + 1 }} · {{ $typeLabel }}
                        </p>

                        <p class="mt-1 text-sm font-bold text-slate-700">
                            Max Score: {{ number_format((float) ($question?->score ?? 0), 2) }}
                        </p>
                    </div>

                    <span class="inline-flex w-max rounded-full px-3 py-1 text-xs font-bold {{ $answerStatusClass }}">
                        {{ $answerStatusLabel }}
                    </span>
                </div>
            </div>

            <div class="space-y-5 p-6">
                <div class="rich-text-content max-w-none">
                    {!! $question?->question !!}
                </div>

                @if ($question?->question_type === 'multiple_choice')
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Student Answer
                    </p>

                    <p class="mt-2 text-base font-bold text-slate-900">
                        {{ $answer->selectedOption?->option_label }}.
                        {{ $answer->selectedOption?->option_text }}
                    </p>

                    <p class="mt-3 text-sm font-semibold text-slate-500">
                        Auto Score:
                        <span class="text-slate-900">
                            {{ number_format((float) $answer->score, 2) }}
                        </span>
                    </p>
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
                        class="mt-3 inline-flex h-10 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-sm font-bold text-white transition hover:opacity-90">
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
                        Student Answer
                    </p>

                    <p class="mt-3 whitespace-pre-line text-base leading-7 text-slate-800">
                        {{ $answer->answer_text ?: '-' }}
                    </p>
                </div>
                @endif

                @if ($question?->explanation)
                <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-500">
                        Explanation
                    </p>

                    <div class="rich-text-content mt-3 max-w-none">
                        {!! $question->explanation !!}
                    </div>
                </div>
                @endif

                @if ($isManual)
                <div class="grid gap-4 border-t border-slate-200 pt-5 md:grid-cols-[220px_minmax(0,1fr)]">
                    <div>
                        <label class="block text-sm font-extrabold text-slate-900">
                            Score
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            max="{{ (float) ($question?->score ?? 0) }}"
                            name="answers[{{ $answer->id }}][score]"
                            value="{{ old('answers.' . $answer->id . '.score', $answer->score) }}"
                            class="mt-2 h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-900 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">

                        <p class="mt-2 text-xs leading-5 text-slate-400">
                            Max: {{ number_format((float) ($question?->score ?? 0), 2) }}
                        </p>

                        @error('answers.' . $answer->id . '.score')
                        <p class="mt-2 text-xs font-semibold text-rose-600">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-extrabold text-slate-900">
                            Feedback
                        </label>

                        <textarea
                            name="answers[{{ $answer->id }}][feedback]"
                            rows="4"
                            placeholder="Write feedback for this answer..."
                            class="mt-2 w-full resize-y rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-6 text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">{{ old('answers.' . $answer->id . '.feedback', $answer->feedback) }}</textarea>
                    </div>
                </div>
                @else
                @if ($answer->feedback)
                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Feedback
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        {{ $answer->feedback }}
                    </p>
                </div>
                @endif
                @endif
            </div>
        </x-admin.table-card>
        @endforeach

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-end">
            <a
                href="{{ route('admin.course-management.final-exam-reviews.index', $finalExam) }}"
                class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-sm transition hover:opacity-95">
                Save Review
            </button>
        </div>
    </form>
</section>
@endsection