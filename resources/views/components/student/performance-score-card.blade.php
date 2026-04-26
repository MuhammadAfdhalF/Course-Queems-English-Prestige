@props([
'score' => 85,
'total' => 100,
'percentileText' => 'You scored higher than 72% of other students in this module.',
])

<div class="rounded-[24px] border border-slate-200 bg-white px-8 py-8 shadow-sm">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-lg font-bold uppercase tracking-[0.18em] text-slate-400">
                Your Final Performance
            </p>

            <div class="mt-4 flex items-end gap-3">
                <span class="text-[72px] font-bold leading-none text-[var(--color-brand-blue)]">
                    {{ $score }}
                </span>
                <span class="pb-2 text-[24px] font-semibold text-slate-400">
                    / {{ $total }}
                </span>
            </div>
        </div>

        <div class="shrink-0">
            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-sm font-bold uppercase tracking-[0.1em] text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12l2.5 2.5L16 9" />
                </svg>
                Submitted
            </span>
        </div>
    </div>

    <div class="mt-8">
        <div class="h-4 overflow-hidden rounded-full bg-slate-200">
            <div
                class="h-full rounded-full bg-[var(--color-brand-blue)] transition-all duration-700 ease-out"
                style="width: {{ $score }}%"></div>
        </div>

        <p class="mt-5 text-lg text-slate-500">
            {{ $percentileText }}
        </p>
    </div>
</div>