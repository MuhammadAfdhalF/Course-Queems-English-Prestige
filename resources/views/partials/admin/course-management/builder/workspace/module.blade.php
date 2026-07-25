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
            <a
                href="{{ route('admin.course-management.modules.materials.create', $module->id) }}"
                class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-3.5 py-2 text-xs font-bold text-white shadow-sm hover:opacity-90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Material</span>
            </a>

            <a
                href="{{ route('admin.course-management.levels.modules.index', $level->id) }}"
                class="text-xs font-bold text-slate-400 hover:text-slate-700 hover:underline px-1">
                Legacy Page &rarr;
            </a>
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
            <span class="rounded-full bg-slate-100 px-2 py-0.2 text-[10px] text-slate-600">{{ $module->materials->count() }}</span>
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
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800">Materials List ({{ $module->materials->count() }})</h3>
                    <div class="flex items-center gap-2">
                        <a
                            href="{{ route('admin.course-management.modules.materials.create', $module->id) }}"
                            class="inline-flex items-center gap-1 rounded-lg bg-[var(--color-brand-blue)] px-3 py-1.5 text-xs font-bold text-white shadow-sm hover:opacity-90">
                            + Add Material
                        </a>
                        <a
                            href="{{ route('admin.course-management.modules.materials.index', $module->id) }}"
                            class="text-xs font-bold text-slate-500 hover:text-slate-800 hover:underline">
                            Legacy Materials Page &rarr;
                        </a>
                    </div>
                </div>

                @if ($module->materials->isEmpty())
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
                        <p class="text-xs font-semibold text-slate-500">No materials uploaded for this module yet.</p>
                        <div class="mt-3">
                            <a
                                href="{{ route('admin.course-management.modules.materials.create', $module->id) }}"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-4 py-2 text-xs font-bold text-white shadow-sm hover:opacity-90">
                                <span>Upload First Material</span> &rarr;
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <table class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3">Sort</th>
                                    <th class="px-4 py-3">Title</th>
                                    <th class="px-4 py-3">Type</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($module->materials as $mat)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-3 font-extrabold text-slate-400">#{{ $mat->sort_order }}</td>
                                    <td class="px-4 py-3 font-bold text-slate-800">{{ $mat->title }}</td>
                                    <td class="px-4 py-3 uppercase font-semibold text-blue-600">{{ $mat->material_type ?? 'Document/Video' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $mat->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $mat->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a
                                            href="{{ route('admin.course-management.modules.materials.index', $module->id) }}"
                                            class="text-xs font-bold text-[var(--color-brand-blue)] hover:underline">
                                            Manage &rarr;
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
                        <p class="mt-2 text-3xl font-black text-slate-800">{{ $module->materials->count() }} Items</p>
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
