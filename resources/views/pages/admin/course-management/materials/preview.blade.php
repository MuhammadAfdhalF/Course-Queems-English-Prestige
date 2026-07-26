@extends('layouts.admin', [
'pageTitle' => 'Preview Module Content',
'pageSubtitle' => $module->title,
])

@section('content')
@php
    $builderUrl = route('admin.course-management.programs.builder', [
        'courseProgram' => $module->courseLevel->course_program_id,
        'level' => $module->course_level_id,
        'module' => $module->id,
        'tab' => 'materials'
    ]);
@endphp

<section class="mx-auto max-w-6xl space-y-6">
    <x-admin.page-toolbar
        :back-url="$builderUrl"
        back-label="Back to Course Builder" />

    <x-admin.table-card class="p-6">
        <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Module Preview
                </p>

                <h2 class="mt-2 text-2xl font-bold text-slate-900">
                    {{ $module->title }}
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    {{ $module->courseLevel->courseProgram->name }}
                    —
                    {{ $module->courseLevel->name }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2 md:justify-end">
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                    {{ $materials->count() }} material blocks
                </span>

                @if ($module->is_preview)
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                    Preview Module
                </span>
                @endif

                @if ($module->is_active)
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                    Active
                </span>
                @else
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                    Inactive
                </span>
                @endif
            </div>
        </div>
    </x-admin.table-card>

    @if ($materials->isEmpty())
    <x-admin.table-card class="p-10 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
            <x-admin.icon name="file" class="h-6 w-6" />
        </div>

        <h3 class="mt-4 text-lg font-bold text-slate-900">
            No materials to preview
        </h3>

        <p class="mt-2 text-sm text-slate-500">
            Create material blocks first to preview this module content.
        </p>

        <a
            href="{{ route('admin.course-management.modules.materials.create', $module) }}"
            class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Add Material</span>
        </a>
    </x-admin.table-card>
    @else
    <article class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-10">
        <header class="border-b border-slate-200 pb-8">
            <p class="text-sm font-bold text-[var(--color-brand-blue)]">
                {{ $module->courseLevel->courseProgram->name }}
                —
                {{ $module->courseLevel->name }}
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 md:text-4xl">
                {{ $module->title }}
            </h1>

            @if ($module->short_description)
            <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600">
                {{ $module->short_description }}
            </p>
            @endif
        </header>

        <div class="mt-8 space-y-10">
            @foreach ($materials as $material)
            @include('partials.admin.course-management.materials.preview-block', [
            'material' => $material,
            'loopIndex' => $loop->iteration,
            ])
            @endforeach
        </div>
    </article>
    @endif
</section>
@endsection