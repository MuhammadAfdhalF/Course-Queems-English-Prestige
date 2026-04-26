<div class="space-y-6">
    <div class="rounded-[24px] border border-blue-100 bg-[#F7FAFF] px-6 py-6 shadow-sm">
        <h3 class="text-[34px] font-bold text-[var(--color-brand-blue)]">
            Next Steps
        </h3>

        <p class="mt-5 text-[21px] leading-9 text-slate-600">
            You have successfully completed all modules of this course. You can now return to your dashboard or explore new learning opportunities.
        </p>

        <a
            href="{{ route('student.my-courses') }}"
            class="mt-8 inline-flex h-14 w-full items-center justify-center gap-3 rounded-xl border-2 border-[var(--color-brand-blue)] bg-white px-8 text-2xl font-bold text-[var(--color-brand-blue)] transition hover:bg-blue-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>
            Back to My Courses
        </a>
    </div>

    <div class="rounded-[24px] border border-slate-200 bg-white px-6 py-6 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-[34px] font-bold text-slate-900">
                    Final Certificate
                </h3>

                <p class="mt-2 text-sm font-bold uppercase tracking-[0.16em] text-[var(--color-brand-gold)]">
                    Locked
                </p>
            </div>

            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                </svg>
            </div>
        </div>

        <p class="mt-6 text-lg leading-8 text-slate-400">
            Submit your testimonial for this course to unlock and download your official certificate of completion.
        </p>

        <div class="mt-8 space-y-4">
            <div class="flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12l2.5 2.5L16 9" />
                    </svg>
                </span>
                <span class="text-lg text-slate-400 line-through">Final exam passed</span>
            </div>

            <div class="flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-slate-300 bg-white"></span>
                <span class="text-2xl font-semibold text-slate-700">Testimonial submitted</span>
            </div>
        </div>

        <a
            href="#"
            class="mt-8 inline-flex h-14 w-full items-center justify-center gap-3 rounded-xl bg-[var(--color-brand-blue)] px-8 text-2xl font-bold text-white shadow-md transition hover:opacity-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="5" y="3" width="14" height="18" rx="2" stroke-width="1.8" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8M8 11h8M8 15h5" />
            </svg>
            Write Testimonial
        </a>
    </div>
</div>