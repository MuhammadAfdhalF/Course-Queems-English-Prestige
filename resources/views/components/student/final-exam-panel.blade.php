<div class="relative overflow-hidden rounded-[26px] bg-gradient-to-r from-[#071738] via-[#081B4A] to-[#0A2059] px-7 py-8 text-white shadow-[0_18px_45px_rgba(15,23,42,0.18)]">
    <div class="relative z-10 flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
        <div class="max-w-2xl">
            <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-slate-200">
                Certification Milestone
            </span>

            <h3 class="mt-6 text-[38px] font-bold leading-tight text-white">
                Course Final Exam
            </h3>

            <p class="mt-4 max-w-xl text-lg leading-8 text-blue-100">
                This comprehensive exam covers all materials from the 6 modules. Your certificate will be generated automatically upon passing.
            </p>

            <p class="mt-5 inline-flex items-center gap-2 text-base font-semibold text-[var(--color-brand-gold)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Ready to begin your final assessment
            </p>
        </div>

        <div class="shrink-0">
            <a
                href="{{ route('student.final-exam') }}"
                class="inline-flex h-14 min-w-[240px] items-center justify-center gap-3 rounded-2xl bg-white px-8 text-lg font-bold text-[var(--color-brand-blue)] shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                Start Final Exam
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 5v14l11-7-11-7z" />
                </svg>
            </a>
        </div>
    </div>

    <div class="pointer-events-none absolute -right-10 top-8 h-40 w-40 rotate-12 rounded-[32px] border-[14px] border-white/10"></div>
</div>