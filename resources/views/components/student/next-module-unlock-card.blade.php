<div class="rounded-[24px] border border-blue-100 bg-[#F7FAFF] px-6 py-6 shadow-sm">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#FFD400] text-slate-900 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="6" y="10" width="12" height="10" rx="2" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10V8a4 4 0 118 0v2" />
                </svg>
            </div>

            <div>
                <h3 class="text-[30px] font-bold leading-tight text-slate-900">
                    Next module is now unlocked
                </h3>
                <p class="mt-2 text-[24px] font-semibold text-[var(--color-brand-blue)]">
                    Next: Module 2 – Listening Strategies
                </p>
            </div>
        </div>

        <a
            href="{{ route('student.learning-path') }}"
            class="inline-flex items-center gap-2 text-xl font-bold text-[var(--color-brand-blue)] transition hover:opacity-80">
            Sneak Peek
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M13 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</div>