@props([
'practice',
'question' => null,
'action',
'method' => 'POST',
'submitLabel' => 'Save Question',
'nextSortOrder' => null,
])

@php
$isEdit = filled($question);
$currentType = old('question_type', $question?->question_type ?? 'multiple_choice');

$options = collect($question?->options ?? [])->keyBy('option_label');
$correctOption = old('correct_option', optional($options->firstWhere('is_correct', true))->option_label ?? 'A');
@endphp

<x-admin.table-card>
    <form
        x-data="{
            questionType: @js($currentType)
        }"
        action="{{ $action }}"
        method="POST"
        class="space-y-8 p-6">
        @csrf

        @if ($method !== 'POST')
        @method($method)
        @endif

        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Question Information
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Configure a question for this module practice.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <x-admin.form.select
                label="Question Type"
                name="question_type"
                id="question_type"
                x-model="questionType"
                :value="$currentType"
                :options="[
                    'multiple_choice' => 'Multiple Choice',
                    'short_answer' => 'Short Answer',
                    'essay' => 'Essay',
                    'upload' => 'Upload File',
                ]"
                :required="true" />

            <x-admin.form.input
                label="Score"
                name="score"
                id="score"
                type="number"
                min="0"
                step="0.01"
                :value="old('score', $question?->score ?? 1)"
                :required="true" />

            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="sort_order"
                type="number"
                min="0"
                :value="old('sort_order', $question?->sort_order ?? $nextSortOrder ?? 0)" />
        </div>

        <x-admin.form.rich-text
            label="Question"
            name="question"
            id="question"
            :value="old('question', $question?->question)"
            hint="Write the question content. You can add formatted text, images, and tables."
            :height="420"
            :required="true" />

        <div x-show="questionType === 'multiple_choice'" class="space-y-5 rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <div>
                <h3 class="text-lg font-bold text-slate-900">
                    Multiple Choice Options
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Fill options A-D and choose one correct answer.
                </p>
            </div>

            @foreach (['A', 'B', 'C', 'D'] as $label)
            <div class="grid gap-3 md:grid-cols-[auto_1fr] md:items-start">
                <label class="flex h-12 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-700">
                    <input
                        type="radio"
                        name="correct_option"
                        value="{{ $label }}"
                        @checked($correctOption===$label)
                        class="text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">
                    {{ $label }}
                </label>

                <x-admin.form.textarea
                    label="Option {{ $label }}"
                    name="options[{{ $label }}]"
                    id="option_{{ $label }}"
                    :value="old('options.' . $label, $options[$label]->option_text ?? '')"
                    rows="2"
                    placeholder="Write option {{ $label }}..." />
            </div>
            @endforeach
        </div>

        <x-admin.form.rich-text
            label="Explanation"
            name="explanation"
            id="explanation"
            :value="old('explanation', $question?->explanation)"
            hint="Optional. Add explanation or discussion after the answer is submitted."
            :height="320" />

        <x-admin.form.checkbox
            label="Active"
            name="is_active"
            id="is_active"
            :checked="old('is_active', $question?->is_active ?? true)" />

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
            <a
                href="{{ route('admin.course-management.practices.questions.index', $practice) }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                {{ $submitLabel }}
            </button>
        </div>
    </form>
</x-admin.table-card>