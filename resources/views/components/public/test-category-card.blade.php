@props([
'title' => 'Grammar',
'duration' => '15 mins',
'description' => 'Short description',
])

<div {{ $attributes->merge(['class' => 'group motion-card rounded-[20px] border border-slate-200 bg-white p-6 shadow-sm']) }}>
    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#eef4ff] text-[var(--color-brand-blue)]">
        {{ $icon ?? '' }}
    </div>

    <div class="mt-5">
        <div class="flex items-start justify-between gap-3">
            <h3 class="text-[22px] font-bold leading-tight text-slate-900">
                {{ $title }}
            </h3>

            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                {{ $duration }}
            </span>
        </div>

        <p class="mt-4 text-sm leading-7 text-slate-500">
            {{ $description }}
        </p>
    </div>

    <div class="mt-6">
        <span class="inline-flex items-center gap-2 text-sm font-bold text-[var(--color-brand-blue)]">
            <span>Start Test</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="motion-link-arrow h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-6-6l6 6-6 6" />
            </svg>
        </span>
    </div>
</div>