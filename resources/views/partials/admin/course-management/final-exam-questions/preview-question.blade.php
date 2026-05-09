@php
$typeLabels = [
'multiple_choice' => 'Multiple Choice',
'short_answer' => 'Short Answer',
'essay' => 'Essay',
'upload' => 'Upload File',
];

$typeLabel = $typeLabels[$question->question_type] ?? $question->question_type;
@endphp

<section class="final-exam-preview-question">
    <div class="mb-4 flex flex-wrap items-center gap-2">
        <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-lg bg-slate-100 px-2 text-xs font-bold text-slate-500">
            #{{ $loopIndex }}
        </span>

        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
            {{ $typeLabel }}
        </span>

        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
            {{ number_format((float) $question->score, 2) }} points
        </span>

        @if (! $question->is_active)
        <span class="inline-flex rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-500">
            Inactive
        </span>
        @endif
    </div>

    <div class="rich-text-content">
        {!! $question->question !!}
    </div>

    <div class="mt-5">
        @if ($question->question_type === 'multiple_choice')
        <div class="space-y-3">
            @foreach ($question->options->sortBy('sort_order') as $option)
            <label class="flex cursor-not-allowed items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <input
                    type="radio"
                    disabled
                    class="mt-1 text-[var(--color-brand-blue)]">

                <span class="text-sm leading-6 text-slate-700">
                    <span class="font-bold text-slate-900">
                        {{ $option->option_label }}.
                    </span>
                    {{ $option->option_text }}
                </span>
            </label>
            @endforeach
        </div>

        <div class="mt-4 rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
            <p class="text-sm font-bold text-emerald-800">
                Correct answer:
                {{ optional($question->options->firstWhere('is_correct', true))->option_label ?? '-' }}
            </p>
        </div>

        @elseif ($question->question_type === 'short_answer')
        <input
            type="text"
            disabled
            placeholder="Student short answer will appear here..."
            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500">

        @elseif ($question->question_type === 'essay')
        <textarea
            disabled
            rows="6"
            placeholder="Student essay answer will appear here..."
            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500"></textarea>

        @elseif ($question->question_type === 'upload')
        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
            <p class="text-sm font-bold text-slate-700">
                Upload Answer File
            </p>

            <p class="mt-1 text-sm text-slate-500">
                Student will upload their answer file here.
            </p>

            <button
                type="button"
                disabled
                class="mt-4 inline-flex cursor-not-allowed items-center justify-center rounded-xl bg-slate-200 px-5 py-3 text-sm font-bold text-slate-500">
                Choose File
            </button>
        </div>
        @endif
    </div>

    @if ($question->explanation)
    <details class="mt-5 rounded-2xl border border-slate-200 bg-white p-4">
        <summary class="cursor-pointer text-sm font-bold text-slate-700">
            Explanation / Discussion
        </summary>

        <div class="rich-text-content mt-4">
            {!! $question->explanation !!}
        </div>
    </details>
    @endif
</section>