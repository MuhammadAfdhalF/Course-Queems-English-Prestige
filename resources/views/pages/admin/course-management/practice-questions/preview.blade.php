@extends('layouts.admin', [
'pageTitle' => 'Preview Practice',
'pageSubtitle' => $modulePractice->title,
])

@section('content')
@php
    $builderUrl = route('admin.course-management.programs.builder', [
        'courseProgram' => $modulePractice->module->courseLevel->course_program_id,
        'level' => $modulePractice->module->course_level_id,
        'module' => $modulePractice->module->id,
        'tab' => 'practice'
    ]);
@endphp

<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="$builderUrl"
        back-label="Back to Course Builder">
        <x-slot:actions>
            <a
                href="{{ route('admin.course-management.practices.questions.create', $modulePractice) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                <x-admin.icon name="plus" class="h-5 w-5" />
                <span>Add Question</span>
            </a>
        </x-slot:actions>
    </x-admin.page-toolbar>

    <x-admin.table-card class="p-6">
        <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-start">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Practice Preview
                </p>

                <h2 class="mt-2 text-2xl font-bold text-slate-900">
                    {{ $modulePractice->title }}
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    {{ $modulePractice->module->courseLevel->courseProgram->name }}
                    —
                    {{ $modulePractice->module->courseLevel->name }}
                    —
                    {{ $modulePractice->module->title }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2 md:justify-end">
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                    {{ $questions->count() }} questions
                </span>

                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                    {{ ucfirst($modulePractice->grading_method) }}
                </span>

                @if ($modulePractice->is_required)
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                    Required
                </span>
                @else
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                    Optional
                </span>
                @endif
            </div>
        </div>

        @if ($modulePractice->description)
        <div class="rich-text-content mt-6 border-t border-slate-200 pt-6">
            {!! $modulePractice->description !!}
        </div>
        @endif

        <div class="mt-6 grid gap-3 sm:grid-cols-3">
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                    Passing Grade
                </p>
                <p class="mt-2 text-xl font-bold text-slate-900">
                    {{ $modulePractice->passing_grade }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                    Max Attempts
                </p>
                <p class="mt-2 text-xl font-bold text-slate-900">
                    {{ $modulePractice->max_attempts ?? 'Unlimited' }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                    Status
                </p>
                <p class="mt-2 text-xl font-bold text-slate-900">
                    {{ $modulePractice->is_active ? 'Active' : 'Inactive' }}
                </p>
            </div>
        </div>
    </x-admin.table-card>

    @if ($questions->isEmpty())
    <x-admin.table-card class="p-10 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
            <x-admin.icon name="check" class="h-6 w-6" />
        </div>

        <h3 class="mt-4 text-lg font-bold text-slate-900">
            No questions to preview
        </h3>

        <p class="mt-2 text-sm text-slate-500">
            Add questions first to preview this practice.
        </p>

        <a
            href="{{ route('admin.course-management.practices.questions.create', $modulePractice) }}"
            class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Add Question</span>
        </a>
    </x-admin.table-card>
    @else
    <article class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-10">
        <div class="space-y-10">
            @foreach ($questions as $question)
            @include('partials.admin.course-management.practice-questions.preview-question', [
            'question' => $question,
            'loopIndex' => $loop->iteration,
            ])
            @endforeach
        </div>
    </article>
    @endif
</section>
@endsection