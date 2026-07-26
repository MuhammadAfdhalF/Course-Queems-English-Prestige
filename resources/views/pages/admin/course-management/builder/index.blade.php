@extends('layouts.admin', [
    'pageTitle' => 'Course Builder',
    'pageSubtitle' => $courseProgram->name,
])

@push('scripts')
    @vite(['resources/js/course-builder.js', 'resources/js/admin-rich-text.js'])
@endpush

@section('content')
<style>[x-cloak] { display: none !important; }</style>

<section
    x-data="courseBuilder({
        programId: {{ $courseProgram->id }},
        workspaceUrl: '{{ route('admin.course-management.programs.builder.workspace', $courseProgram->id) }}',
        treeUrl: '{{ route('admin.course-management.programs.builder.tree', $courseProgram->id) }}',
        firstLevelId: {{ $firstLevelId ?: 'null' }}
    })"
    class="mx-auto max-w-7xl space-y-5">

    {{-- Compact Responsive Header Bar --}}
    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a
                href="{{ route('admin.course-management.programs.index') }}"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 shadow-sm transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500"
                title="Back to Course Programs"
                aria-label="Back to Course Programs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Course Builder</span>
                    <span class="text-xs text-slate-300">•</span>
                    <span class="inline-flex items-center rounded-full px-2 py-0.2 text-[10px] font-bold uppercase {{ $courseProgram->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $courseProgram->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <h1 class="text-lg font-bold text-slate-900 lg:text-xl truncate">
                    {{ $courseProgram->name }}
                </h1>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Mobile Toggle Structure Tree Button --}}
            <button
                type="button"
                @click="mobileTreeOpen = !mobileTreeOpen"
                aria-label="Toggle Course Structure Navigation"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 lg:hidden focus:outline-none focus:ring-2 focus:ring-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                <span>Structure</span>
            </button>

            {{-- Primary Action: Add Level via Drawer --}}
            <button
                type="button"
                @click="openCreateLevelDrawer('{{ route('admin.course-management.programs.levels.store', $courseProgram->id) }}')"
                class="inline-flex items-center gap-1.5 rounded-xl bg-amber-500 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Level</span>
            </button>
        </div>
    </div>

    {{-- Mobile Off-Canvas Tree Drawer (< 1024px) --}}
    <div
        x-show="mobileTreeOpen"
        x-cloak
        class="relative z-50 lg:hidden"
        role="dialog"
        aria-modal="true"
        aria-label="Course Structure Navigation">

        {{-- Backdrop --}}
        <div
            x-show="mobileTreeOpen"
            x-transition:enter="ease-in-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in-out duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="mobileTreeOpen = false"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

        <div class="fixed inset-y-0 left-0 flex max-w-full">
            <div
                x-show="mobileTreeOpen"
                x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="w-screen max-w-xs bg-white shadow-2xl flex flex-col p-4">

                <div class="mb-3 flex items-center justify-between border-b border-slate-100 pb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Course Structure</span>
                    <button
                        type="button"
                        @click="mobileTreeOpen = false"
                        aria-label="Close navigation"
                        class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto">
                    <div x-show="treeHtml" x-html="treeHtml"></div>
                    <div x-show="!treeHtml">
                        @include('partials.admin.course-management.builder.tree')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Builder 2-Column Grid Layout --}}
    <div class="grid gap-6 lg:grid-cols-12">
        {{-- Left Column: Desktop Tree Navigation --}}
        <div class="hidden lg:block lg:col-span-4 xl:col-span-4">
            <div class="sticky top-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm max-h-[calc(100vh-140px)] overflow-y-auto">
                <div x-show="treeHtml" x-html="treeHtml"></div>
                <div x-show="!treeHtml">
                    @include('partials.admin.course-management.builder.tree')
                </div>
            </div>
        </div>

        {{-- Right Column: Workspace Panel --}}
        <div class="lg:col-span-8 xl:col-span-8">
            @include('partials.admin.course-management.builder.workspace')
        </div>
    </div>

    {{-- Slide-Over Drawer --}}
    @include('partials.admin.course-management.builder.drawer')

    {{-- Delete Modal --}}
    @include('partials.admin.course-management.builder.delete-modal')
</section>
@endsection
