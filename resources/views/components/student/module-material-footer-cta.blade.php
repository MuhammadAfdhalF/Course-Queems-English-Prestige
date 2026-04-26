<div class="rounded-[24px] border border-slate-200 bg-white px-6 py-6 shadow-sm lg:px-8">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                </svg>
            </div>

            <div>
                <h3 class="text-2xl font-bold text-slate-900">
                    Ready for the next step?
                </h3>
                <p class="mt-2 text-base text-slate-500">
                    Complete the practice quiz to unlock Module 2.
                </p>
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">
            <a
                href="{{ route('student.learning-path') }}"
                class="inline-flex h-14 items-center justify-center gap-2 rounded-xl border border-slate-300 bg-slate-50 px-8 text-lg font-bold text-slate-700 transition hover:bg-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Module List
            </a>

            <a
                href="{{ route('student.module-practice') }}"
                class="inline-flex h-14 items-center justify-center gap-2 rounded-xl bg-[#F97316] px-8 text-lg font-bold text-white shadow-lg shadow-orange-200 transition hover:translate-y-[-1px] hover:opacity-95">
                Go to Practice
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M13 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>