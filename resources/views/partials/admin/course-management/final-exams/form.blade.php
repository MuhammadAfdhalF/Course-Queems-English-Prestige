@props([
'courseLevel' => null,
'finalExam' => null,
'nextSortOrder' => null,
'action',
'method' => 'POST',
'submitLabel' => 'Save Final Exam',
])

@php
$isEdit = filled($finalExam);
$backCourseLevel = $courseLevel ?? $finalExam->courseLevel;
@endphp

@php
$initResultMode = old('result_mode', $finalExam?->result_mode?->value ?? (string) $finalExam?->result_mode ?? 'pass_fail');
$initTotalScore = old('total_score', $finalExam?->total_score !== null ? (float) $finalExam->total_score : 100);
$initPassingScore = old('passing_score', $finalExam?->passing_score !== null ? (float) $finalExam->passing_score : 75);
$initMaxAttempts = old('max_attempts', $finalExam?->max_attempts);
$initAttemptMode = old('attempt_mode', $finalExam ? ($finalExam->max_attempts === 1 ? 'one' : ($finalExam->max_attempts > 1 ? 'multiple' : 'unlimited')) : 'unlimited');
$isLocked = $isEdit && $finalExam->attempts()->exists();
@endphp

<x-admin.table-card x-data="{
    resultMode: '{{ $initResultMode }}',
    totalScore: '{{ $initTotalScore }}',
    passingScore: '{{ $initPassingScore }}',
    attemptMode: '{{ $initAttemptMode }}',
    maxAttempts: '{{ $initMaxAttempts }}',
    isLocked: {{ $isLocked ? 'true' : 'false' }}
}">
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

        <template x-if="isLocked">
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-3.5 text-xs font-semibold text-amber-800 flex items-start gap-2">
                <svg class="h-4 w-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>Scoring configuration cannot be changed because this section already has student attempts.</span>
            </div>
        </template>

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

        <div class="grid gap-6 md:grid-cols-3">
            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="sort_order"
                type="number"
                min="1"
                :value="old('sort_order', $finalExam?->sort_order ?? $nextSortOrder ?? 1)"
                placeholder="Example: 1"
                :required="true" />

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Result Type <span class="text-rose-500">*</span></label>
                <select
                    name="result_mode"
                    id="result_mode"
                    x-model="resultMode"
                    :disabled="isLocked"
                    required
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs font-semibold focus:border-purple-500 focus:outline-none disabled:bg-slate-100 disabled:text-slate-500">
                    <option value="pass_fail">Pass / Fail</option>
                    <option value="score_only">Score Only</option>
                </select>
                @error('result_mode')
                    <p class="mt-1 text-[11px] font-bold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

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
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Total Score <span class="text-rose-500">*</span></label>
                <input
                    type="number"
                    step="0.01"
                    min="0.01"
                    name="total_score"
                    id="total_score"
                    x-model="totalScore"
                    :disabled="isLocked"
                    required
                    placeholder="e.g. 100"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs font-semibold focus:border-purple-500 focus:outline-none disabled:bg-slate-100 disabled:text-slate-500" />
                @error('total_score')
                    <p class="mt-1 text-[11px] font-bold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div x-show="resultMode === 'pass_fail'">
                <label class="block text-xs font-bold text-slate-700 mb-1">Minimum Passing Score <span class="text-rose-500">*</span></label>
                <input
                    type="number"
                    step="0.01"
                    min="0.01"
                    :max="totalScore"
                    name="passing_score"
                    id="passing_score"
                    x-model="passingScore"
                    :disabled="isLocked"
                    :required="resultMode === 'pass_fail'"
                    placeholder="e.g. 75"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs font-semibold focus:border-purple-500 focus:outline-none disabled:bg-slate-100 disabled:text-slate-500" />
                @error('passing_score')
                    <p class="mt-1 text-[11px] font-bold text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Helper Text --}}
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-[11px] text-slate-600 space-y-1">
            <template x-if="resultMode === 'pass_fail'">
                <p class="font-medium">Students pass when their earned score is equal to or greater than the minimum passing score.</p>
            </template>
            <template x-if="resultMode === 'score_only'">
                <p class="font-medium">This assessment reports a score without Passed or Failed status.</p>
            </template>
            <div class="flex items-center gap-4 text-slate-500 font-semibold pt-1 border-t border-slate-200/60">
                <span>Score Range: 0–<span x-text="totalScore || 0"></span></span>
                <span x-show="resultMode === 'pass_fail'">Passing Score: <span x-text="passingScore || 0"></span></span>
            </div>
        </div>

        {{-- Attempt Limit --}}
        <div class="space-y-2 pt-2 border-t border-slate-200">
            <label class="block text-xs font-bold text-slate-700">Attempt Limit <span class="text-rose-500">*</span></label>
            <div class="grid gap-3 sm:grid-cols-3">
                <label class="flex items-center gap-2 rounded-xl border border-slate-300 p-2.5 cursor-pointer hover:bg-slate-50">
                    <input type="radio" value="unlimited" x-model="attemptMode" name="attempt_mode" class="h-4 w-4 text-purple-600 focus:ring-purple-500" />
                    <span class="text-xs font-bold text-slate-700">Unlimited</span>
                </label>
                <label class="flex items-center gap-2 rounded-xl border border-slate-300 p-2.5 cursor-pointer hover:bg-slate-50">
                    <input type="radio" value="one" x-model="attemptMode" name="attempt_mode" class="h-4 w-4 text-purple-600 focus:ring-purple-500" />
                    <span class="text-xs font-bold text-slate-700">One Attempt</span>
                </label>
                <label class="flex items-center gap-2 rounded-xl border border-slate-300 p-2.5 cursor-pointer hover:bg-slate-50">
                    <input type="radio" value="multiple" x-model="attemptMode" name="attempt_mode" class="h-4 w-4 text-purple-600 focus:ring-purple-500" />
                    <span class="text-xs font-bold text-slate-700">Multiple</span>
                </label>
            </div>

            <div x-show="attemptMode === 'multiple'" class="pt-2">
                <label class="block text-xs font-bold text-slate-700 mb-1">Maximum Attempts <span class="text-rose-500">*</span></label>
                <input
                    type="number"
                    name="max_attempts"
                    id="max_attempts"
                    min="2"
                    x-model="maxAttempts"
                    :required="attemptMode === 'multiple'"
                    placeholder="e.g. 3"
                    class="w-full sm:w-48 rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs font-semibold focus:border-purple-500 focus:outline-none" />
                @error('max_attempts')
                    <p class="mt-1 text-[11px] font-bold text-rose-600">{{ $message }}</p>
                @enderror
            </div>
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