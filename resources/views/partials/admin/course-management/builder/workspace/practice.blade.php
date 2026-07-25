<div class="space-y-6">
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-emerald-100 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-emerald-800">
                    Module Practice
                </span>
                @if ($practice)
                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $practice->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $practice->is_active ? 'Active' : 'Inactive' }}
                </span>
                @endif
            </div>

            <h2 class="mt-2 text-2xl font-bold text-slate-800">
                {{ $practice ? $practice->title : 'Module Practice' }}
            </h2>
            <p class="text-xs text-slate-500">
                Module: <span class="font-bold text-slate-700">{{ $module->title }}</span> | Level: <span class="font-bold text-slate-700">{{ $level->name }}</span>
            </p>
        </div>

        @if ($practice)
        <div class="flex flex-wrap items-center gap-2">
            <a
                href="{{ route('admin.course-management.practices.questions.index', $practice->id) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-4 py-2 text-xs font-bold text-white shadow-sm hover:opacity-90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Manage Questions</span>
            </a>

            <a
                href="{{ route('admin.course-management.practice-reviews.index', $practice->id) }}"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <span>Review Attempts</span> &rarr;
            </a>
        </div>
        @endif
    </div>

    @if (!$practice)
        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <h4 class="mt-3 text-sm font-bold text-slate-800">No Practice Created</h4>
            <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">This module does not have an active practice quiz configured yet.</p>
            <div class="mt-4">
                <a
                    href="{{ route('admin.course-management.modules.practice.create', $module->id) }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Configure Practice</span>
                </a>
            </div>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Active Questions</span>
                <p class="mt-2 text-3xl font-black text-slate-800">{{ $practice->questions_count }} Q</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Passing Grade</span>
                <p class="mt-2 text-3xl font-black text-emerald-700">{{ $practice->passing_grade }}%</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Max Student Attempts</span>
                <p class="mt-2 text-3xl font-black text-slate-800">{{ $practice->max_attempts ? $practice->max_attempts . 'x' : 'Unlimited' }}</p>
            </div>
        </div>

        @if ($practice->description)
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Practice Instructions</h4>
            <p class="mt-1 text-xs text-slate-700 leading-relaxed">{{ $practice->description }}</p>
        </div>
        @endif
    @endif
</div>
