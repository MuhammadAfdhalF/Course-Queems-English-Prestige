<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-amber-100 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-amber-800">
                    Course Level
                </span>
                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $level->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $level->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <h2 class="mt-2 text-2xl font-bold text-slate-800">
                {{ $level->name }}
            </h2>
            <p class="text-xs text-slate-500">
                Program: <span class="font-bold text-slate-700">{{ $courseProgram->name }}</span> | Price: <span class="font-bold text-slate-700">Rp {{ number_format($level->price, 0, ',', '.') }}</span> | Access: <span class="font-bold text-slate-700">{{ $level->access_duration_days ? $level->access_duration_days . ' days' : 'Lifetime' }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Primary Action: Add Module --}}
            <button
                type="button"
                @click="openCreateModuleDrawer('{{ $level->id }}', '{{ route('admin.course-management.levels.modules.store', $level->id) }}')"
                class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:opacity-90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Module</span>
            </button>

            {{-- Secondary Action: Edit Level --}}
            <button
                type="button"
                @click="openEditLevelDrawer('{{ route('admin.course-management.levels.edit', $level->id) }}')"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit</span>
            </button>

            {{-- Delete Level --}}
            <button
                type="button"
                @click="confirmDelete('level', '{{ $level->id }}', '{{ addslashes($level->name) }}', '{{ route('admin.course-management.levels.destroy', $level->id) }}')"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100"
                title="Delete Level">
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
            @click="selectNode({ level: '{{ $level->id }}', module: null, exam: null, tab: 'overview' })"
            class="border-b-2 px-4 py-2.5 transition"
            :class="selectedParams.tab === 'overview' ? 'border-[var(--color-brand-blue)] text-[var(--color-brand-blue)]' : 'border-transparent text-slate-500 hover:text-slate-700'">
            Overview
        </button>

        <button
            type="button"
            @click="selectNode({ level: '{{ $level->id }}', module: null, exam: null, tab: 'modules' })"
            class="border-b-2 px-4 py-2.5 transition flex items-center gap-1.5"
            :class="selectedParams.tab === 'modules' ? 'border-[var(--color-brand-blue)] text-[var(--color-brand-blue)]' : 'border-transparent text-slate-500 hover:text-slate-700'">
            <span>Modules</span>
            <span class="rounded-full bg-slate-100 px-2 py-0.2 text-[10px] text-slate-600">{{ $level->modules->count() }}</span>
        </button>

        <button
            type="button"
            @click="selectNode({ level: '{{ $level->id }}', module: null, exam: null, tab: 'final-exam' })"
            class="border-b-2 px-4 py-2.5 transition flex items-center gap-1.5"
            :class="selectedParams.tab === 'final-exam' ? 'border-purple-600 text-purple-700' : 'border-transparent text-slate-500 hover:text-slate-700'">
            <span>Final Exam</span>
            <span class="rounded-full bg-purple-100 px-2 py-0.2 text-[10px] text-purple-700">{{ $level->finalExams->count() }}</span>
        </button>
    </div>

    {{-- Tab Content --}}
    <div class="space-y-4">
        @if ($tab === 'modules')
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800">Modules in {{ $level->name }} ({{ $level->modules->count() }})</h3>
                    <button
                        type="button"
                        @click="openCreateModuleDrawer('{{ $level->id }}', '{{ route('admin.course-management.levels.modules.store', $level->id) }}')"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-3.5 py-1.5 text-xs font-bold text-white shadow-sm hover:opacity-90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Add Module</span>
                    </button>
                </div>

                @if ($level->modules->isEmpty())
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
                        <p class="text-xs font-semibold text-slate-500">No modules added to this level yet.</p>
                        <div class="mt-3">
                            <button
                                type="button"
                                @click="openCreateModuleDrawer('{{ $level->id }}', '{{ route('admin.course-management.levels.modules.store', $level->id) }}')"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-3.5 py-2 text-xs font-bold text-white shadow-sm hover:opacity-90">
                                <span>Add First Module</span> &rarr;
                            </button>
                        </div>
                    </div>
                @else
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($level->modules as $mod)
                        @php $prc = $mod->practices->first(); @endphp
                        <div class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-blue-300">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-extrabold text-slate-400">Sort: #{{ $mod->sort_order }}</span>
                                <div class="flex items-center gap-1">
                                    <span class="rounded px-2 py-0.5 text-[10px] font-bold uppercase {{ $mod->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $mod->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <button
                                        type="button"
                                        @click="openEditModuleDrawer('{{ route('admin.course-management.modules.edit', $mod->id) }}')"
                                        class="rounded p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                                        title="Edit Module">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <h4 class="mt-2 text-base font-bold text-slate-800 group-hover:text-[var(--color-brand-blue)]">
                                {{ $mod->title }}
                            </h4>
                            <p class="mt-1 line-clamp-2 text-xs text-slate-500">{{ $mod->short_description ?: 'No description' }}</p>

                            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                                <div class="flex items-center gap-2 text-slate-600 font-semibold">
                                    <span>{{ $mod->materials_count }} Materials</span>
                                    <span>•</span>
                                    <span class="text-emerald-700">{{ $prc ? $prc->questions_count : 0 }} Practice Q</span>
                                </div>

                                <button
                                    type="button"
                                    @click="selectNode({ level: '{{ $level->id }}', module: '{{ $mod->id }}', exam: null, tab: 'overview' })"
                                    class="inline-flex items-center gap-1 rounded-xl bg-blue-50 px-2.5 py-1 text-xs font-bold text-[var(--color-brand-blue)] hover:bg-blue-100">
                                    <span>Open Module</span>
                                    <span>&rarr;</span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif ($tab === 'final-exam')
            @include('partials.admin.course-management.builder.workspace.final-exam-folder', ['level' => $level, 'courseProgram' => $courseProgram])
        @else
            {{-- Default Overview --}}
            <div class="space-y-4">
                @if ($level->short_description)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Level Short Description</h4>
                    <p class="mt-1 text-xs text-slate-700 leading-relaxed">{{ $level->short_description }}</p>
                </div>
                @endif

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Modules Breakdown</h4>
                            <button type="button" @click="selectNode({ level: '{{ $level->id }}', module: null, exam: null, tab: 'modules' })" class="text-xs font-bold text-[var(--color-brand-blue)] hover:underline">View All &rarr;</button>
                        </div>
                        <p class="mt-2 text-3xl font-black text-slate-800">{{ $level->modules->count() }} Modules</p>
                        <p class="mt-1 text-xs text-slate-400">Total Materials: {{ $level->modules->sum('materials_count') }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Final Exam Sections</h4>
                            <button type="button" @click="selectNode({ level: '{{ $level->id }}', module: null, exam: null, tab: 'final-exam' })" class="text-xs font-bold text-purple-700 hover:underline">View All &rarr;</button>
                        </div>
                        <p class="mt-2 text-3xl font-black text-purple-800">{{ $level->finalExams->count() }} Sections</p>
                        <p class="mt-1 text-xs text-slate-400">Total Exam Questions: {{ $level->finalExams->sum('questions_count') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
