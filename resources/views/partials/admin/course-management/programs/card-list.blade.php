<div class="space-y-4">
    {{-- Reorder Disabled Hint Banner --}}
    <template x-if="!isReorderEnabled">
        <div class="flex items-center gap-2.5 rounded-2xl border border-amber-200 bg-amber-50/80 px-4 py-3 text-xs font-semibold text-amber-800 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Drag-and-drop reordering is temporarily disabled while searching or filtering. Select 'All' status filter and clear search box to reorder.</span>
        </div>
    </template>

    {{-- Cards List Container --}}
    @if ($coursePrograms->isNotEmpty())
        <div class="space-y-3">
            @foreach ($coursePrograms as $index => $program)
            @php
                $modulesCount = $program->courseLevels->sum('modules_count');
                $finalExamsCount = $program->courseLevels->sum('final_exams_count');
                $editPayload = [
                    'id' => $program->id,
                    'name' => $program->name,
                    'slug' => $program->slug,
                    'sort_order' => $program->sort_order,
                    'is_active' => (bool) $program->is_active,
                    'update_url' => route('admin.course-management.programs.update', $program->id),
                ];
                $deletePayload = [
                    'title' => $program->name,
                    'delete_url' => route('admin.course-management.programs.destroy', $program->id),
                ];
            @endphp
            <div
                x-show="filteredPrograms.some(p => p.id === {{ $program->id }})"
                :draggable="isReorderEnabled"
                @dragstart="onDragStart({{ $index }}, $event)"
                @dragover.prevent
                @drop="onDrop({{ $index }})"
                class="group relative flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition duration-200 hover:border-blue-300 hover:shadow-md md:flex-row md:items-center md:justify-between">

                {{-- Left Details & Drag Handle --}}
                <div class="flex items-start gap-3 flex-1 overflow-hidden">
                    {{-- Drag Handle --}}
                    <div
                        :class="isReorderEnabled ? 'cursor-grab active:cursor-grabbing text-slate-400 hover:text-slate-600' : 'cursor-not-allowed text-slate-200'"
                        class="flex items-center pt-1"
                        title="Drag to reorder">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </div>

                    {{-- Main Info --}}
                    <div class="space-y-1.5 flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            {{-- Order Badge --}}
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-extrabold text-slate-600">
                                #{{ $index + 1 }}
                            </span>

                            {{-- Title --}}
                            <h3 class="truncate text-base font-bold text-slate-900 group-hover:text-[var(--color-brand-blue)]">
                                {{ $program->name }}
                            </h3>

                            {{-- Status Badge --}}
                            <span
                                class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $program->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $program->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <p class="truncate text-xs font-medium text-slate-400">{{ $program->slug }}</p>

                        {{-- Stats Summary --}}
                        <div class="flex items-center gap-3 text-xs font-semibold text-slate-600 pt-0.5">
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                                <span>{{ $program->course_levels_count }} Levels</span>
                            </span>
                            <span class="text-slate-300">•</span>
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>{{ $modulesCount }} Modules</span>
                            </span>
                            <span class="text-slate-300">•</span>
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                </svg>
                                <span>{{ $finalExamsCount }} Exam Sections</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Right Action Buttons --}}
                <div class="flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3 md:border-t-0 md:pt-0">
                    {{-- Mobile Move Up / Down Buttons --}}
                    <template x-if="isReorderEnabled">
                        <div class="flex items-center gap-1">
                            <button
                                type="button"
                                @click="moveProgram({{ $index }}, {{ $index - 1 }})"
                                :disabled="{{ $index }} === 0"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600 transition hover:bg-slate-200 disabled:opacity-30"
                                title="Move Up">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                @click="moveProgram({{ $index }}, {{ $index + 1 }})"
                                :disabled="{{ $index }} === {{ count($coursePrograms) - 1 }}"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600 transition hover:bg-slate-200 disabled:opacity-30"
                                title="Move Down">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                    </template>

                    {{-- Primary: Open Builder --}}
                    <a
                        href="{{ route('admin.course-management.programs.builder', $program->id) }}"
                        class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-4 text-xs font-bold text-white shadow-sm transition hover:opacity-90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Open Builder</span>
                    </a>

                    {{-- Edit --}}
                    <button
                        type="button"
                        @click='openEditModal({{ \Illuminate\Support\Js::from($editPayload) }})'
                        class="inline-flex h-9 items-center justify-center gap-1 rounded-xl bg-slate-100 px-3 text-xs font-bold text-slate-700 transition hover:bg-slate-200"
                        title="Edit Program">
                        <x-admin.icon name="pencil" class="h-3.5 w-3.5" />
                        <span>Edit</span>
                    </button>

                    {{-- Delete Icon --}}
                    <button
                        type="button"
                        @click='openDeleteModal({{ \Illuminate\Support\Js::from($deletePayload) }})'
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100"
                        title="Delete Program">
                        <x-admin.icon name="trash" class="h-3.5 w-3.5" />
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- Empty State --}}
    <div
        x-show="filteredPrograms.length === 0"
        x-cloak
        class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
        <h3 class="mt-4 text-sm font-bold text-slate-800">No course programs found</h3>
        <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">Try adjusting your search query or filter, or create a new course program.</p>
        <div class="mt-4">
            <button
                type="button"
                @click="createModalOpen = true"
                class="inline-flex items-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:opacity-90">
                <x-admin.icon name="plus" class="h-4 w-4" />
                <span>Add Course Program</span>
            </button>
        </div>
    </div>

    {{-- Sticky Save Reorder Bar --}}
    <div
        x-show="isOrderChanged"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 flex items-center justify-between gap-6 rounded-2xl bg-slate-900 px-6 py-3.5 text-white shadow-2xl backdrop-blur-md">
        <div class="flex items-center gap-3">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
            </span>
            <span class="text-xs font-bold">Program order has changed</span>
        </div>

        <div class="flex items-center gap-2">
            <button
                type="button"
                @click="discardOrder()"
                class="rounded-xl border border-slate-700 px-3.5 py-1.5 text-xs font-bold text-slate-300 transition hover:bg-slate-800">
                Discard
            </button>
            <button
                type="button"
                @click="saveOrder()"
                class="rounded-xl bg-amber-600 px-4 py-1.5 text-xs font-bold text-white shadow transition hover:bg-amber-700">
                Save Order
            </button>
        </div>
    </div>
</div>
