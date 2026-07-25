@extends('layouts.admin', [
    'pageTitle' => 'Course Builder',
    'pageSubtitle' => $courseProgram->name,
])

@push('scripts')
    @vite(['resources/js/course-builder.js'])
@endpush

@section('content')
<section
    x-data="courseBuilder({
        programId: {{ $courseProgram->id }},
        workspaceUrl: '{{ route('admin.course-management.programs.builder.workspace', $courseProgram->id) }}',
        firstLevelId: {{ $firstLevelId ?: 'null' }}
    })"
    class="mx-auto max-w-7xl space-y-5">

    {{-- Compact Header Bar --}}
    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a
                href="{{ route('admin.course-management.programs.index') }}"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 shadow-sm transition hover:bg-slate-100 hover:text-slate-900"
                title="Back to Course Programs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Course Builder</span>
                    <span class="text-xs text-slate-300">•</span>
                    <span class="inline-flex items-center rounded-full px-2 py-0.2 text-[10px] font-bold uppercase {{ $courseProgram->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $courseProgram->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <h1 class="text-lg font-bold text-slate-900 lg:text-xl">
                    {{ $courseProgram->name }}
                </h1>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Mobile Toggle Tree Button --}}
            <button
                type="button"
                @click="mobileTreeOpen = !mobileTreeOpen"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                <span>Structure Tree</span>
            </button>

            {{-- Primary Action: Add Level --}}
            <a
                href="{{ route('admin.course-management.programs.levels.create', $courseProgram->id) }}"
                class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:opacity-90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Level</span>
            </a>

            {{-- Secondary Action: Manage Levels --}}
            <a
                href="{{ route('admin.course-management.programs.levels.index', $courseProgram->id) }}"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Legacy Page</span>
            </a>
        </div>
    </div>

    {{-- Main Builder 2-Column Grid Layout --}}
    <div class="grid gap-6 lg:grid-cols-12">
        {{-- Left Column: Tree Navigation (Desktop Always Visible, Mobile Collapsible Drawer) --}}
        <div
            :class="mobileTreeOpen ? 'block' : 'hidden lg:block'"
            class="lg:col-span-4 xl:col-span-4">
            <div class="sticky top-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm max-h-[calc(100vh-140px)] overflow-y-auto">
                <div class="mb-3 flex items-center justify-between border-b border-slate-100 pb-2 lg:hidden">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Course Structure</span>
                    <button
                        type="button"
                        @click="mobileTreeOpen = false"
                        class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @include('partials.admin.course-management.builder.tree')
            </div>
        </div>

        {{-- Right Column: Workspace Panel --}}
        <div class="lg:col-span-8 xl:col-span-8">
            @include('partials.admin.course-management.builder.workspace')
        </div>
    </div>
</section>
@endsection
