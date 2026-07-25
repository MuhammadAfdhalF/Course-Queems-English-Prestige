<div class="space-y-6">
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-purple-100 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-purple-800">
                    Final Exam Section
                </span>
                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $exam->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $exam->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <h2 class="mt-2 text-2xl font-bold text-slate-800">
                {{ $exam->title }}
            </h2>
            <p class="text-xs text-slate-500">
                Level: <span class="font-bold text-slate-700">{{ $level->name }}</span> | Sort Order: <span class="font-bold text-slate-700">#{{ $exam->sort_order }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a
                href="{{ route('admin.course-management.final-exams.questions.index', $exam->id) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-purple-700 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-purple-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Manage Questions</span>
            </a>

            <a
                href="{{ route('admin.course-management.final-exam-reviews.index', $exam->id) }}"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <span>Review Attempts</span> &rarr;
            </a>

            <a
                href="{{ route('admin.course-management.final-exams.edit', $exam->id) }}"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 shadow-sm transition hover:bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit Section</span>
            </a>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-purple-100 bg-purple-50/50 p-4 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Total Questions</span>
            <p class="mt-2 text-3xl font-black text-purple-900">{{ $exam->questions_count }} Q</p>
        </div>

        <div class="rounded-2xl border border-purple-100 bg-purple-50/50 p-4 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Passing Grade</span>
            <p class="mt-2 text-3xl font-black text-purple-900">{{ $exam->passing_grade }}%</p>
        </div>

        <div class="rounded-2xl border border-purple-100 bg-purple-50/50 p-4 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Max Attempts</span>
            <p class="mt-2 text-3xl font-black text-purple-900">{{ $exam->max_attempts ? $exam->max_attempts . 'x' : 'Unlimited' }}</p>
        </div>
    </div>

    @if ($exam->description)
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Section Description / Instructions</h4>
        <p class="mt-1 text-xs text-slate-700 leading-relaxed">{{ $exam->description }}</p>
    </div>
    @endif
</div>
