<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-slate-900 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-amber-400">
                    Program Overview
                </span>
                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $courseProgram->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $courseProgram->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <h2 class="mt-2 text-2xl font-bold text-slate-800">
                {{ $courseProgram->name }}
            </h2>
            <p class="text-xs text-slate-400">Slug: {{ $courseProgram->slug }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button
                type="button"
                @click="openCreateLevelDrawer('{{ route('admin.course-management.programs.levels.store', $courseProgram->id) }}')"
                class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-4 py-2 text-xs font-bold text-white shadow-sm hover:opacity-90 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Level</span>
            </button>
        </div>
    </div>

    {{-- Program Stat Cards --}}
    <div class="grid gap-3 grid-cols-2 sm:grid-cols-3 lg:grid-cols-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Levels</span>
            <p class="mt-1 text-2xl font-black text-slate-800">{{ $totalLevels ?? $courseProgram->courseLevels->count() }}</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Modules</span>
            <p class="mt-1 text-2xl font-black text-blue-600">{{ $totalModules ?? 0 }}</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Materials</span>
            <p class="mt-1 text-2xl font-black text-slate-700">{{ $totalMaterials ?? 0 }}</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Practices</span>
            <p class="mt-1 text-2xl font-black text-emerald-600">{{ $totalPractices ?? 0 }}</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm col-span-2 sm:col-span-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Exam Sections</span>
            <p class="mt-1 text-2xl font-black text-purple-700">{{ $totalFinalExams ?? 0 }}</p>
        </div>
    </div>

    {{-- Levels Structure Section --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">Course Levels in {{ $courseProgram->name }}</h3>
            <div class="flex items-center gap-2">
                @if ($courseProgram->courseLevels->count() > 1)
                    <button
                        type="button"
                        x-show="!reorderMode"
                        @click="startReorder('levels', 'Reorder Course Levels', '{{ route('admin.course-management.programs.builder.levels.reorder', $courseProgram->id) }}', {{ json_encode($courseProgram->courseLevels->map(fn($l) => ['id' => $l->id, 'title' => $l->name, 'sort_order' => $l->sort_order])->values()) }})"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-xs hover:bg-slate-50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                        <span>Reorder Levels</span>
                    </button>
                @endif
                <button
                    type="button"
                    x-show="!reorderMode"
                    @click="openCreateLevelDrawer('{{ route('admin.course-management.programs.levels.store', $courseProgram->id) }}')"
                    class="text-xs font-bold text-[var(--color-brand-blue)] hover:underline">
                    + Add Level
                </button>
            </div>
        </div>

        @include('partials.admin.course-management.builder.workspace.reorder-bar')

        @if ($courseProgram->courseLevels->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-amber-50 text-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h4 class="mt-3 text-sm font-bold text-slate-800">No Course Levels yet</h4>
                <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">Get started by creating the first level for this course program.</p>
                <div class="mt-4">
                    <button
                        type="button"
                        @click="openCreateLevelDrawer('{{ route('admin.course-management.programs.levels.store', $courseProgram->id) }}')"
                        class="inline-flex items-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:opacity-90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Add First Level</span>
                    </button>
                </div>
            </div>
        @else
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($courseProgram->courseLevels as $lvl)
                <div class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-blue-300">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-slate-400">Sort: #{{ $lvl->sort_order }}</span>
                        <div class="flex items-center gap-1.5">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $lvl->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $lvl->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <button
                                type="button"
                                @click="openEditLevelDrawer('{{ route('admin.course-management.levels.edit', $lvl->id) }}')"
                                class="rounded p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                                title="Edit Level">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <h4 class="mt-2 text-base font-bold text-slate-900 group-hover:text-[var(--color-brand-blue)]">
                        {{ $lvl->name }}
                    </h4>
                    <p class="mt-1 text-xs font-semibold text-slate-500">
                        Rp {{ number_format($lvl->price, 0, ',', '.') }} | Access: {{ $lvl->access_duration_days ? $lvl->access_duration_days . ' days' : 'Lifetime' }}
                    </p>

                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                        <div class="flex items-center gap-2 font-semibold text-slate-600">
                            <span>{{ $lvl->modules_count }} Modules</span>
                            <span>•</span>
                            <span class="text-purple-700">{{ $lvl->final_exams_count }} Exam Sections</span>
                        </div>

                        <button
                            type="button"
                            @click="selectNode({ level: '{{ $lvl->id }}', module: null, exam: null, tab: 'overview' })"
                            class="inline-flex items-center gap-1 rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-[var(--color-brand-blue)] transition hover:bg-blue-100">
                            <span>Open Level</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
