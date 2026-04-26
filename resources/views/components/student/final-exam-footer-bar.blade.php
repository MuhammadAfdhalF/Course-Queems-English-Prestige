<div class="flex flex-col gap-4 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
            Time Remaining
        </p>
        <p class="mt-2 inline-flex items-center gap-2 text-2xl font-bold text-slate-900">
            <span class="text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 7v5l3 3" />
                </svg>
            </span>
            <span x-text="formattedTime"></span>
        </p>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <a
            href="{{ route('student.learning-path') }}"
            class="inline-flex h-12 items-center justify-center rounded-xl px-6 text-base font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
            Back to Module List
        </a>

        <button
            type="button"
            @click="submitExam()"
            class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-8 text-base font-bold text-white shadow-md transition hover:opacity-95">
            Submit Final Exam
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M13 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>