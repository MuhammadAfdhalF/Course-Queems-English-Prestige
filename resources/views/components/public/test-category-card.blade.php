@props([
    'title' => 'Grammar',
    'duration' => '15 mins',
    'description' => 'Short test description.',
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 bg-white p-5 shadow-sm']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-[#2457E6]">
            {{ $icon ?? '' }}
        </div>

        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">
            {{ $duration }}
        </span>
    </div>

    <h3 class="mt-5 text-[18px] font-bold text-slate-900">
        {{ $title }}
    </h3>

    <p class="mt-3 min-h-[72px] text-sm leading-6 text-slate-500">
        {{ $description }}
    </p>

    <div class="mt-5">
        <a
            href="#"
            class="inline-flex w-full items-center justify-center rounded-xl bg-[#F4BE1A] px-5 py-3 text-sm font-bold text-slate-900 transition hover:opacity-90"
        >
            Start Test
        </a>
    </div>
</div>