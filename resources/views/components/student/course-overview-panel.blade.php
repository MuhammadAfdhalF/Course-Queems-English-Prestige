<div x-data="{ open: true }" class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
    <button
        type="button"
        @click="open = !open"
        class="flex w-full items-center justify-between px-6 py-5 text-left lg:px-8">
        <div class="flex items-center gap-3">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8h.01M11 12h1v4h1" />
                </svg>
            </span>
            <h2 class="text-2xl font-bold text-slate-900">Course Overview</h2>
        </div>

        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-transition class="border-t border-slate-200 px-6 py-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="space-y-6">
                <div>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-900">
                        <span class="text-[var(--color-brand-blue)]">◉</span>
                        Learning Objectives
                    </h3>
                    <p class="mt-3 text-lg leading-8 text-slate-600">
                        Master all four sections of the TOEFL iBT (Reading, Listening, Speaking, Writing).
                        Gain structural familiarity and time management skills essential for a high score.
                    </p>
                </div>

                <div>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-900">
                        <span class="text-[var(--color-brand-blue)]">⌘</span>
                        Target Audience
                    </h3>
                    <p class="mt-3 text-lg leading-8 text-slate-600">
                        Students aiming for a score of 80+ for university admissions or professional certification.
                    </p>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-900">
                        <span class="text-[var(--color-brand-blue)]">◉</span>
                        Outcomes
                    </h3>
                    <p class="mt-3 text-lg leading-8 text-slate-600">
                        Receive a verified Certificate of Completion and a comprehensive score projection report based on practice tests.
                    </p>
                </div>

                <div>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-900">
                        <span class="text-[var(--color-brand-blue)]">⌁</span>
                        Graduation Rules
                    </h3>
                    <p class="mt-3 text-lg leading-8 text-slate-600">
                        Complete all 6 learning modules and achieve at least 70% in the Final Exam to qualify for graduation.
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <a
                href="#"
                class="inline-flex h-12 items-center justify-center rounded-xl border border-[var(--color-brand-blue)] bg-white px-8 text-lg font-bold text-[var(--color-brand-blue)] transition hover:bg-blue-50">
                Start Learning
            </a>
        </div>
    </div>
</div>