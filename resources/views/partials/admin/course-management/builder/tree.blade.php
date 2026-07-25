<div class="space-y-3 text-xs font-medium">
    {{-- Program Root Node --}}
    <div class="border-b border-slate-200 pb-3">
        <button
            type="button"
            @click="selectNode({ level: null, module: null, exam: null, tab: 'overview' })"
            class="group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left transition duration-150"
            :class="!selectedParams.level && !selectedParams.module && !selectedParams.exam ? 'bg-slate-900 text-white font-bold shadow-sm' : 'text-slate-800 hover:bg-slate-100'">
            <div class="flex items-center gap-2 truncate">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" :class="!selectedParams.level ? 'text-amber-400' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="truncate font-bold text-xs">{{ $courseProgram->name }}</span>
            </div>

            <span
                :class="!selectedParams.level ? 'bg-amber-400/20 text-amber-300' : 'bg-slate-100 text-slate-600'"
                class="shrink-0 rounded-md px-1.5 py-0.5 text-[10px] font-extrabold">
                {{ $courseProgram->courseLevels->count() }} Lvl
            </span>
        </button>
    </div>

    {{-- Tree Node Items --}}
    @if ($courseProgram->courseLevels->isEmpty())
        <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 py-6 text-center text-xs text-slate-400 font-semibold">
            No course levels added yet.
        </div>
    @else
        <ul class="space-y-1.5">
            @foreach ($courseProgram->courseLevels as $level)
            @php $levelNodeId = 'level_' . $level->id; @endphp
            <li class="space-y-1">
                {{-- Level Header Node --}}
                <div
                    class="group flex items-center justify-between rounded-xl px-2.5 py-1.5 transition duration-150 cursor-pointer"
                    :class="selectedParams.level == '{{ $level->id }}' && !selectedParams.module && !selectedParams.exam && selectedParams.tab !== 'final-exam' ? 'bg-amber-50 text-amber-900 border-l-4 border-amber-500 font-bold shadow-sm' : 'text-slate-700 hover:bg-slate-100'"
                    @click="selectNode({ level: '{{ $level->id }}', module: null, exam: null, tab: 'overview' })">

                    <div class="flex items-center gap-1.5 flex-1 overflow-hidden">
                        <button
                            type="button"
                            @click.stop="toggleNode('{{ $levelNodeId }}')"
                            class="p-0.5 text-slate-400 hover:text-slate-700">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-3.5 w-3.5 transition-transform duration-200"
                                :class="isNodeExpanded('{{ $levelNodeId }}') ? 'rotate-90' : ''"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>

                        <span class="truncate text-xs">{{ $level->name }}</span>
                    </div>

                    <div class="flex items-center gap-1">
                        @if(!$level->is_active)
                            <span class="shrink-0 rounded bg-slate-200 px-1 py-0.2 text-[9px] font-bold text-slate-500 uppercase">Off</span>
                        @endif
                        <span class="shrink-0 rounded bg-slate-100 px-1.5 py-0.2 text-[10px] font-bold text-slate-600">
                            {{ $level->modules->count() }} Mod
                        </span>
                    </div>
                </div>

                {{-- Level Children Subtree --}}
                <ul
                    x-show="isNodeExpanded('{{ $levelNodeId }}')"
                    x-collapse
                    class="ml-3 space-y-1 border-l-2 border-slate-200 pl-2.5">

                    @if ($level->modules->isEmpty())
                        <li class="py-1 pl-2 text-[11px] italic text-slate-400">No modules in this level</li>
                    @else
                        @foreach ($level->modules as $module)
                        @php $moduleNodeId = 'module_' . $module->id; @endphp
                        <li class="space-y-0.5">
                            {{-- Module Node --}}
                            <div
                                class="group flex items-center justify-between rounded-lg px-2 py-1 transition duration-150 cursor-pointer"
                                :class="selectedParams.module == '{{ $module->id }}' && selectedParams.tab === 'overview' ? 'bg-blue-50 text-[var(--color-brand-blue)] border-l-3 border-[var(--color-brand-blue)] font-bold' : 'text-slate-600 hover:bg-slate-100'"
                                @click="selectNode({ level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'overview' })">

                                <div class="flex items-center gap-1.5 flex-1 overflow-hidden">
                                    <button
                                        type="button"
                                        @click.stop="toggleNode('{{ $moduleNodeId }}')"
                                        class="p-0.5 text-slate-400 hover:text-slate-600">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-3.5 w-3.5 transition-transform duration-200"
                                            :class="isNodeExpanded('{{ $moduleNodeId }}') ? 'rotate-90' : ''"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>

                                    <span class="truncate text-xs">{{ $module->title }}</span>
                                </div>
                            </div>

                            {{-- Module Sub-items: Materials & Practice --}}
                            <ul
                                x-show="isNodeExpanded('{{ $moduleNodeId }}')"
                                x-collapse
                                class="ml-4 space-y-0.5 border-l border-slate-200 pl-2 text-[11px]">
                                {{-- Materials --}}
                                <li>
                                    <button
                                        type="button"
                                        @click="selectNode({ level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'materials' })"
                                        class="flex w-full items-center justify-between rounded px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800"
                                        :class="selectedParams.module == '{{ $module->id }}' && selectedParams.tab === 'materials' ? 'bg-blue-50 text-[var(--color-brand-blue)] font-bold' : ''">
                                        <span class="flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            Materials
                                        </span>
                                        <span class="rounded bg-slate-100 px-1.5 py-0.2 text-[10px] text-slate-600 font-semibold">{{ $module->materials_count }}</span>
                                    </button>
                                </li>

                                {{-- Practice --}}
                                @php $practice = $module->practices->first(); @endphp
                                <li>
                                    <button
                                        type="button"
                                        @click="selectNode({ level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'practice' })"
                                        class="flex w-full items-center justify-between rounded px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800"
                                        :class="selectedParams.module == '{{ $module->id }}' && selectedParams.tab === 'practice' ? 'bg-emerald-50 text-emerald-700 font-bold' : ''">
                                        <span class="flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                            Practice
                                        </span>
                                        <span class="rounded bg-slate-100 px-1.5 py-0.2 text-[10px] text-slate-600 font-semibold">
                                            {{ $practice ? $practice->questions_count : 0 }} Q
                                        </span>
                                    </button>
                                </li>
                            </ul>
                        </li>
                        @endforeach
                    @endif

                    {{-- Final Exam Folder Node --}}
                    @php $examFolderNodeId = 'level_' . $level->id . '_exam'; @endphp
                    <li class="space-y-0.5">
                        <div
                            class="group flex items-center justify-between rounded-lg px-2 py-1 transition duration-150 cursor-pointer"
                            :class="selectedParams.level == '{{ $level->id }}' && selectedParams.tab === 'final-exam' && !selectedParams.exam ? 'bg-purple-50 text-purple-800 border-l-3 border-purple-600 font-bold' : 'text-slate-600 hover:bg-slate-100'"
                            @click="selectNode({ level: '{{ $level->id }}', module: null, exam: null, tab: 'final-exam' })">

                            <div class="flex items-center gap-1.5 flex-1 overflow-hidden">
                                <button
                                    type="button"
                                    @click.stop="toggleNode('{{ $examFolderNodeId }}')"
                                    class="p-0.5 text-slate-400 hover:text-slate-600">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-3.5 w-3.5 transition-transform duration-200"
                                        :class="isNodeExpanded('{{ $examFolderNodeId }}') ? 'rotate-90' : ''"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>

                                <span class="truncate text-xs">Final Exam</span>
                            </div>

                            <span class="rounded bg-purple-100 px-1.5 py-0.2 text-[10px] font-bold text-purple-700">
                                {{ $level->finalExams->count() }} Sec
                            </span>
                        </div>

                        {{-- Final Exam Sections --}}
                        <ul
                            x-show="isNodeExpanded('{{ $examFolderNodeId }}')"
                            x-collapse
                            class="ml-4 space-y-0.5 border-l border-slate-200 pl-2 text-[11px]">
                            @if ($level->finalExams->isEmpty())
                                <li class="py-1 pl-2 text-[11px] italic text-slate-400">No exam sections in this level</li>
                            @else
                                @foreach ($level->finalExams as $examSection)
                                <li>
                                    <button
                                        type="button"
                                        @click="selectNode({ level: '{{ $level->id }}', module: null, exam: '{{ $examSection->id }}', tab: 'overview' })"
                                        class="flex w-full items-center justify-between rounded px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800"
                                        :class="selectedParams.exam == '{{ $examSection->id }}' ? 'bg-purple-50 text-purple-700 font-bold' : ''">
                                        <span class="flex items-center gap-1.5 truncate">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="truncate">{{ $examSection->title }}</span>
                                        </span>
                                        <span class="shrink-0 rounded bg-slate-100 px-1.5 py-0.2 text-[10px] font-bold text-slate-600">
                                            {{ $examSection->questions_count }} Q
                                        </span>
                                    </button>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                    </li>
                </ul>
            </li>
            @endforeach
        </ul>
    @endif
</div>
