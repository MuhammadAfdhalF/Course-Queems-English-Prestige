<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-blue-100 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-blue-800">
                    Module
                </span>
                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $module->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $module->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if ($module->is_preview)
                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-700">
                    Free Preview
                </span>
                @endif
            </div>

            <h2 class="mt-2 text-2xl font-bold text-slate-800">
                {{ $module->title }}
            </h2>
            <p class="text-xs text-slate-400">
                Level: <span class="font-bold text-slate-600">{{ $level->name }}</span> | Slug: <span class="font-bold text-slate-600">{{ $module->slug }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Edit Module --}}
            <button
                type="button"
                @click="openEditModuleDrawer('{{ route('admin.course-management.modules.edit', $module->id) }}')"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit Module</span>
            </button>

            {{-- Delete Module --}}
            <button
                type="button"
                @click="confirmDelete('module', '{{ $module->id }}', '{{ addslashes($module->title) }}', '{{ route('admin.course-management.modules.destroy', $module->id) }}', { level: '{{ $level->id }}', module: null, exam: null, tab: 'overview' })"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100"
                title="Delete Module">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Tabs Bar --}}
    <div class="flex border-b border-slate-200 text-xs font-bold">
        <button
            type="button"
            @click="selectNode({ level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'overview' })"
            class="border-b-2 px-4 py-2.5 transition"
            :class="selectedParams.tab === 'overview' ? 'border-[var(--color-brand-blue)] text-[var(--color-brand-blue)]' : 'border-transparent text-slate-500 hover:text-slate-700'">
            Overview
        </button>

        <button
            type="button"
            @click="selectNode({ level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'materials' })"
            class="border-b-2 px-4 py-2.5 transition flex items-center gap-1.5"
            :class="selectedParams.tab === 'materials' ? 'border-[var(--color-brand-blue)] text-[var(--color-brand-blue)]' : 'border-transparent text-slate-500 hover:text-slate-700'">
            <span>Materials</span>
            <span class="rounded-full bg-slate-100 px-2 py-0.2 text-[10px] text-slate-600">{{ $selectedModule->materials_count ?? $module->materials->count() }}</span>
        </button>

        <button
            type="button"
            @click="selectNode({ level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'practice' })"
            class="border-b-2 px-4 py-2.5 transition flex items-center gap-1.5"
            :class="selectedParams.tab === 'practice' ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-slate-500 hover:text-slate-700'">
            <span>Practice</span>
            @php $practice = $module->practices->first(); @endphp
            <span class="rounded-full bg-emerald-100 px-2 py-0.2 text-[10px] text-emerald-700">{{ $practice ? $practice->questions_count : 0 }} Q</span>
        </button>
    </div>

    {{-- Content --}}
    <div class="space-y-4">
        @if ($tab === 'materials')
            @php
                $materialsList = isset($materialsPaginator) ? $materialsPaginator->items() : $module->materials;
            @endphp
            <div class="space-y-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Learning Materials ({{ $selectedModule->materials_count ?? count($materialsList) }})</h3>
                        <p class="text-xs text-slate-400">Manage all materials for {{ $module->title }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route('admin.course-management.modules.materials.index', $module->id) }}"
                            class="text-xs font-medium text-slate-400 hover:text-slate-600 hover:underline">
                            Legacy Materials Page &rarr;
                        </a>

                        <button
                            type="button"
                            @click="openCreateMaterialDrawer('{{ $module->id }}', '{{ route('admin.course-management.programs.builder.materials.store', ['courseProgram' => $courseProgram->id, 'module' => $module->id]) }}')"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-3.5 py-2 text-xs font-bold text-white shadow-sm transition hover:opacity-90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>+ Add Material</span>
                        </button>
                    </div>
                </div>

                @if (empty($materialsList) || count($materialsList) === 0)
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="mt-3 text-sm font-bold text-slate-800">No materials yet</h4>
                        <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">Add the first material to this module to provide learning content to students.</p>
                        <div class="mt-4">
                            <button
                                type="button"
                                @click="openCreateMaterialDrawer('{{ $module->id }}', '{{ route('admin.course-management.programs.builder.materials.store', ['courseProgram' => $courseProgram->id, 'module' => $module->id]) }}')"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-4 py-2 text-xs font-bold text-white shadow-sm hover:opacity-90">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Add First Material</span>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="grid gap-3">
                        @foreach ($materialsList as $mat)
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-blue-200">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-3">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-xs font-extrabold text-slate-600 shrink-0">
                                        #{{ $mat->sort_order }}
                                    </span>

                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-blue-700">
                                                {{ $mat->material_type }}
                                            </span>
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $mat->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                                {{ $mat->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>

                                        <h4 class="mt-1 text-sm font-bold text-slate-900">
                                            {{ $mat->title }}
                                        </h4>

                                        @if ($mat->material_type === 'text')
                                        <p class="mt-1 text-xs text-slate-500 line-clamp-1">
                                            {{ Str::limit(strip_tags($mat->content), 120) }}
                                        </p>
                                        @elseif ($mat->file_path)
                                        <p class="mt-1 text-[11px] text-slate-400 font-mono">
                                            {{ basename($mat->file_path) }}
                                        </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 border-t border-slate-100 pt-3 sm:border-t-0 sm:pt-0 shrink-0">
                                    {{-- Open / Preview Action --}}
                                    @if ($mat->material_type === 'text')
                                    <button
                                        type="button"
                                        @click="openEditMaterialDrawer('{{ route('admin.course-management.programs.builder.materials.edit', ['courseProgram' => $courseProgram->id, 'moduleMaterial' => $mat->id]) }}')"
                                        class="inline-flex items-center gap-1 rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
                                        <span>Read / Edit</span>
                                    </button>
                                    @elseif ($mat->file_path)
                                    <a
                                        href="{{ asset('storage/' . $mat->file_path) }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1 rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-[var(--color-brand-blue)] hover:bg-blue-100 transition">
                                        <span>Preview File</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    @endif

                                    {{-- Edit Material --}}
                                    <button
                                        type="button"
                                        @click="openEditMaterialDrawer('{{ route('admin.course-management.programs.builder.materials.edit', ['courseProgram' => $courseProgram->id, 'moduleMaterial' => $mat->id]) }}')"
                                        class="rounded-xl border border-slate-200 p-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition"
                                        title="Edit Material">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    {{-- Delete Material --}}
                                    <button
                                        type="button"
                                        @click="confirmDelete('material', '{{ $mat->id }}', '{{ addslashes($mat->title) }}', '{{ route('admin.course-management.programs.builder.materials.destroy', ['courseProgram' => $courseProgram->id, 'moduleMaterial' => $mat->id]) }}', { level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'materials' })"
                                        class="rounded-xl bg-rose-50 p-1.5 text-rose-600 hover:bg-rose-100 transition"
                                        title="Delete Material">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if (isset($materialsPaginator) && $materialsPaginator->hasPages())
                        <div class="flex items-center justify-between border-t border-slate-200 pt-4 text-xs font-semibold">
                            <span class="text-slate-500">Page {{ $materialsPaginator->currentPage() }} of {{ $materialsPaginator->lastPage() }}</span>
                            <div class="flex items-center gap-2">
                                @if (!$materialsPaginator->onFirstPage())
                                    <button
                                        type="button"
                                        @click="selectNode({ level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'materials', page: {{ $materialsPaginator->currentPage() - 1 }} })"
                                        class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-100">
                                        &larr; Previous
                                    </button>
                                @endif
                                @if ($materialsPaginator->hasMorePages())
                                    <button
                                        type="button"
                                        @click="selectNode({ level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'materials', page: {{ $materialsPaginator->currentPage() + 1 }} })"
                                        class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-100">
                                        Next &rarr;
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @elseif ($tab === 'practice')
            @include('partials.admin.course-management.builder.workspace.practice', [
                'courseProgram' => $courseProgram,
                'level' => $level,
                'module' => $module,
                'practice' => $module->practices->first(),
            ])
        @else
            {{-- Overview --}}
            <div class="space-y-4">
                @if ($module->short_description)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Module Short Description</h4>
                    <p class="mt-1 text-xs text-slate-700 leading-relaxed">{{ $module->short_description }}</p>
                </div>
                @endif

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Materials Count</h4>
                            <button type="button" @click="selectNode({ level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'materials' })" class="text-xs font-bold text-[var(--color-brand-blue)] hover:underline">Manage Materials &rarr;</button>
                        </div>
                        <p class="mt-2 text-3xl font-black text-slate-800">{{ $selectedModule->materials_count ?? $module->materials->count() }} Items</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Module Practice</h4>
                            <button type="button" @click="selectNode({ level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'practice' })" class="text-xs font-bold text-emerald-700 hover:underline">Practice Details &rarr;</button>
                        </div>
                        @php $prc = $module->practices->first(); @endphp
                        <p class="mt-2 text-3xl font-black text-emerald-800">{{ $prc ? $prc->questions_count : 0 }} Questions</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
