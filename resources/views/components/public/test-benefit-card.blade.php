@props([
    'title' => 'Benefit Title',
    'description' => 'Benefit description.',
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 bg-white p-6 shadow-sm']) }}>
    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-[#2457E6]">
        {{ $icon ?? '' }}
    </div>

    <h3 class="mt-5 text-2xl font-bold text-slate-900 lg:text-[18px]">
        {{ $title }}
    </h3>

    <p class="mt-3 text-sm leading-7 text-slate-500">
        {{ $description }}
    </p>
</div>