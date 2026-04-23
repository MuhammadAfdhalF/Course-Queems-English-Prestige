@props([
'title' => 'Grammar',
'duration' => '10 Minutes',
'questions' => '10 Questions',
'description' => 'Test description goes here.',
'href' => '#',
])

<div {{ $attributes->merge(['class' => 'rounded-[22px] border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-[var(--color-brand-gold)]">
            {{ $icon ?? '' }}
        </div>

        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">
            Free Test
        </span>
    </div>

    <div class="mt-5">
        <h3 class="text-xl font-bold text-slate-900">
            {{ $title }}
        </h3>

        <p class="mt-3 text-sm leading-7 text-slate-600">
            {{ $description }}
        </p>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-2">
        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
            {{ $questions }}
        </span>

        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
            {{ $duration }}
        </span>

        <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
            No login required
        </span>
    </div>

    <div class="mt-6">
        <a
            href="{{ $href }}"
            class="inline-flex items-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-semibold text-white transition hover:opacity-90">
            <span>Start Test</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-6-6l6 6-6 6" />
            </svg>
        </a>
    </div>
</div>