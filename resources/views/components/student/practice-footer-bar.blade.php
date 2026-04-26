<div class="flex flex-col gap-4 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
    <a href="{{ route('student.learning-path') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Module List
    </a>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="inline-flex items-center gap-2 rounded-full px-1 py-1 text-sm font-semibold text-slate-600">
            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
            <span>Unanswered: <span x-text="unansweredCount"></span></span>
        </div>

        <button
            type="button"
            @click="submitPractice()"
            class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-8 text-base font-bold text-white shadow-md transition hover:opacity-95">
            Submit Practice
        </button>
    </div>
</div>