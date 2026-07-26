@extends('layouts.admin', [
'pageTitle' => 'Module Practice',
'pageSubtitle' => $module->title,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.levels.modules.index', $module->courseLevel)"
        back-label="Back to Modules" />

    @php
        $builderUrl = route('admin.course-management.programs.builder', [
            'courseProgram' => $module->courseLevel->course_program_id,
            'level' => $module->course_level_id,
            'module' => $module->id,
            'tab' => 'practice'
        ]);
    @endphp

    @include('partials.admin.course-management.legacy-builder-banner', ['builderUrl' => $builderUrl])

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Module
                </p>

                <h2 class="mt-2 text-2xl font-bold text-slate-900">
                    {{ $module->title }}
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    {{ $module->courseLevel->courseProgram->name }}
                    —
                    {{ $module->courseLevel->name }}
                </p>

                @if ($module->short_description)
                <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
                    {{ $module->short_description }}
                </p>
                @endif
            </div>

            <div class="flex flex-wrap gap-2 md:justify-end">
                @if ($module->is_active)
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                    Active Module
                </span>
                @else
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                    Inactive Module
                </span>
                @endif
            </div>
        </div>
    </x-admin.table-card>

    @if (! $practice)
    <x-admin.table-card class="p-10 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-700">
            <x-admin.icon name="check" class="h-6 w-6" />
        </div>

        <h3 class="mt-4 text-lg font-bold text-slate-900">
            No practice yet
        </h3>

        <p class="mx-auto mt-2 max-w-xl text-sm leading-6 text-slate-500">
            This module does not have a practice yet. Create one practice to evaluate students after they finish the module materials.
        </p>

        <a
            href="{{ route('admin.course-management.modules.practice.create', $module) }}"
            class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Create Practice</span>
        </a>
    </x-admin.table-card>
    @else
    <x-admin.table-card class="p-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                        {{ ucfirst($practice->grading_method) }}
                    </span>

                    @if ($practice->is_required)
                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                        Required
                    </span>
                    @else
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                        Optional
                    </span>
                    @endif

                    @if ($practice->is_active)
                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                        Active
                    </span>
                    @else
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                        Inactive
                    </span>
                    @endif
                </div>

                <h3 class="mt-4 text-2xl font-bold text-slate-900">
                    {{ $practice->title }}
                </h3>

                @if ($practice->description)
                <div class="rich-text-content mt-5 max-w-none">
                    {!! $practice->description !!}
                </div>
                @else
                <p class="mt-4 text-sm text-slate-500">
                    No practice instruction.
                </p>
                @endif

                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Passing Grade
                        </p>
                        <p class="mt-2 text-xl font-bold text-slate-900">
                            {{ ($practice->result_mode->value ?? $practice->result_mode) === 'pass_fail' ? number_format((float) $practice->passing_score, 2) : 'Score Only' }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Max Attempts
                        </p>
                        <p class="mt-2 text-xl font-bold text-slate-900">
                            {{ $practice->max_attempts ?? 'Unlimited' }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Questions
                        </p>
                        <p class="mt-2 text-xl font-bold text-slate-900">
                            {{ $practice->questions_count }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex shrink-0 flex-col gap-3 sm:flex-row lg:flex-col">
                <a
                    href="{{ route('admin.course-management.practices.edit', $practice) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <x-admin.icon name="pencil" class="h-4 w-4" />
                    <span>Edit Practice</span>
                </a>

                <a
                    href="{{ route('admin.course-management.practices.preview', $practice) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <x-admin.icon name="eye" class="h-4 w-4" />
                    <span>Preview Practice</span>
                </a>

                <a
                    href="{{ route('admin.course-management.practices.questions.index', $practice) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                    <x-admin.icon name="eye" class="h-4 w-4" />
                    <span>Manage Questions</span>
                </a>

                <a
                    href="{{ route('admin.course-management.practice-reviews.index', $practice) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-50 px-5 py-3 text-sm font-bold text-amber-700 shadow-sm transition hover:bg-amber-100">
                    <x-admin.icon name="practice" class="h-4 w-4" />
                    <span>Review Attempts</span>
                </a>
            </div>
        </div>
    </x-admin.table-card>
    @endif
</section>
@endsection