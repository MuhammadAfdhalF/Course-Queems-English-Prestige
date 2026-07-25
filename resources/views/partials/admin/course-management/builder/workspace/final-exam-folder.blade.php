<div class="space-y-6">
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-purple-100 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-purple-800">
                    Final Exam Folder
                </span>
                <span class="inline-flex rounded-full bg-purple-50 px-2.5 py-0.5 text-xs font-bold text-purple-700">
                    {{ $level->finalExams->count() }} Section{{ $level->finalExams->count() > 1 ? 's' : '' }}
                </span>
            </div>

            <h2 class="mt-2 text-2xl font-bold text-slate-800">
                Final Exam Sections for {{ $level->name }}
            </h2>
            <p class="text-xs text-slate-500">
                Program: <span class="font-bold text-slate-700">{{ $courseProgram->name }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a
                href="{{ route('admin.course-management.levels.final-exam.create', $level->id) }}"
                class="inline-flex items-center gap-1.5 rounded-xl bg-purple-700 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-purple-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Final Exam Section</span>
            </a>
        </div>
    </div>

    @if ($level->finalExams->isEmpty())
        <div class="rounded-2xl border border-dashed border-purple-200 bg-purple-50/50 p-8 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 text-purple-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                </svg>
            </div>
            <h4 class="mt-3 text-sm font-bold text-purple-900">No Final Exam Sections Created</h4>
            <p class="mt-1 text-xs text-purple-700 max-w-sm mx-auto">Create exam sections (e.g. Listening, Structure, Reading) for this level.</p>
            <div class="mt-4">
                <a
                    href="{{ route('admin.course-management.levels.final-exam.create', $level->id) }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-purple-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-purple-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add First Exam Section</span>
                </a>
            </div>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($level->finalExams as $examSection)
            <div class="group rounded-2xl border border-purple-100 bg-white p-4 shadow-sm transition hover:border-purple-300">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold text-slate-400">Sort: #{{ $examSection->sort_order }}</span>
                    <span class="rounded px-2 py-0.5 text-[10px] font-bold uppercase {{ $examSection->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $examSection->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <h3 class="mt-2 text-base font-bold text-slate-800 group-hover:text-purple-700">
                    {{ $examSection->title }}
                </h3>
                <p class="mt-1 line-clamp-2 text-xs text-slate-500">{{ $examSection->description ?: 'No description' }}</p>

                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                    <span class="font-semibold text-purple-800">{{ $examSection->questions_count }} Questions | Passing: {{ $examSection->passing_grade }}%</span>
                    <button
                        type="button"
                        @click="selectNode({ level: '{{ $level->id }}', module: null, exam: '{{ $examSection->id }}', tab: 'overview' })"
                        class="inline-flex items-center gap-1 rounded-xl bg-purple-50 px-3 py-1.5 text-xs font-bold text-purple-700 hover:bg-purple-100">
                        <span>Open Section</span>
                        <span>&rarr;</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
