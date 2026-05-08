@props([
'module' => null,
'practice' => null,
'action',
'method' => 'POST',
'submitLabel' => 'Save Practice',
])

@php
$isEdit = filled($practice);
$backModule = $module ?? $practice->module;
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
                Practice Information
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Configure the practice for this module. One module can only have one practice.
            </p>
        </div>

        <x-admin.form.input
            label="Practice Title"
            name="title"
            id="title"
            :value="old('title', $practice?->title)"
            placeholder="Example: Grammar Foundation Practice"
            :required="true" />

        <x-admin.form.rich-text
            label="Description / Instruction"
            name="description"
            id="description"
            :value="old('description', $practice?->description)"
            hint="Write instructions for students before they start the practice."
            :height="420" />

        <div class="grid gap-6 md:grid-cols-3">
            <x-admin.form.input
                label="Passing Grade"
                name="passing_grade"
                id="passing_grade"
                type="number"
                min="0"
                max="100"
                :value="old('passing_grade', $practice?->passing_grade ?? 0)"
                placeholder="Example: 70"
                :required="true" />

            <x-admin.form.select
                label="Grading Method"
                name="grading_method"
                id="grading_method"
                :value="old('grading_method', $practice?->grading_method ?? 'auto')"
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
                :value="old('max_attempts', $practice?->max_attempts)"
                placeholder="Leave empty for unlimited" />
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.checkbox
                label="Required to continue"
                name="is_required"
                id="is_required"
                :checked="old('is_required', $practice?->is_required ?? true)" />

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="is_active"
                :checked="old('is_active', $practice?->is_active ?? true)" />
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
            <a
                href="{{ route('admin.course-management.modules.practice.index', $backModule) }}"
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