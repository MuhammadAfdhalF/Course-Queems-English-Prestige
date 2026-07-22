@props([
'courseLevel' => null,
'finalExam' => null,
'action',
'method' => 'POST',
'submitLabel' => 'Save Final Exam',
])

@php
$isEdit = filled($finalExam);
$backCourseLevel = $courseLevel ?? $finalExam->courseLevel;
@endphp

<x-admin.table-card>
    <form
        action="{{ $action }}"
        method="POST"
        class="space-y-8 p-6">
        @csrf

        @if ($method !== 'POST')
        @method($method)
        @endif

        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Final Exam Information
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Configure this Final Exam section for the course level. You can add multiple sections (e.g., Listening, Structure, Reading).
            </p>
        </div>

        <x-admin.form.input
            label="Section Title"
            name="title"
            id="title"
            :value="old('title', $finalExam?->title)"
            placeholder="Example: Listening Comprehension Section"
            :required="true" />

        <x-admin.form.rich-text
            label="Description / Instruction"
            name="description"
            id="description"
            :value="old('description', $finalExam?->description)"
            hint="Write instructions for students before they start this section."
            :height="420" />

        <div class="grid gap-6 md:grid-cols-4">
            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="sort_order"
                type="number"
                min="1"
                :value="old('sort_order', $finalExam?->sort_order ?? 1)"
                placeholder="Example: 1"
                :required="true" />

            <x-admin.form.input
                label="Passing Grade"
                name="passing_grade"
                id="passing_grade"
                type="number"
                min="0"
                max="100"
                :value="old('passing_grade', $finalExam?->passing_grade ?? 0)"
                placeholder="Example: 70"
                :required="true" />

            <x-admin.form.select
                label="Grading Method"
                name="grading_method"
                id="grading_method"
                :value="old('grading_method', $finalExam?->grading_method ?? 'auto')"
                :options="[
                    'auto' => 'Auto',
                    'manual' => 'Manual',
                    'mixed' => 'Mixed',
                ]"
                :required="true" />

            <x-admin.form.input
                label="Max Attempts"
                name="max_attempts"
                id="max_attempts"
                type="number"
                min="1"
                :value="old('max_attempts', $finalExam?->max_attempts)"
                placeholder="Leave empty for unlimited" />
        </div>

        <x-admin.form.checkbox
            label="Active"
            name="is_active"
            id="is_active"
            :checked="old('is_active', $finalExam?->is_active ?? true)" />

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
            <a
                href="{{ route('admin.course-management.levels.final-exam.index', $backCourseLevel) }}"
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