@props([
'title' => 'Course Title',
'level' => 'Intermediate',
'mode' => 'Online',
'price' => 'Rp. 100.000',
'description' => 'Short description about this course.',
'image' => 'https://placehold.co/800x500',
'buttonText' => 'View Detail',
])

@php
$modeClasses = $mode === 'Offline'
? 'bg-[#CFE2D7] text-[var(--color-brand-blue)]'
: 'bg-[#58E19A] text-[var(--color-brand-blue)]';

$levelClasses = 'bg-[#D4A017] text-white';
@endphp

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-[18px] border border-slate-200 bg-white shadow-sm']) }}>
    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
        <img src="{{ $image }}" alt="{{ $title }}" class="h-full w-full object-cover">

        <div class="absolute left-3 top-3">
            <span class="inline-flex items-center rounded-md px-3 py-1 text-[10px] font-bold uppercase tracking-[0.12em] {{ $modeClasses }}">
                {{ $mode }}
            </span>
        </div>

        <div class="absolute right-3 top-3">
            <span class="inline-flex items-center rounded-md px-3 py-1 text-[10px] font-bold uppercase tracking-[0.12em] {{ $levelClasses }}">
                {{ $level }}
            </span>
        </div>
    </div>

    <div class="space-y-4 p-5">
        <div>
            <h3 class="text-[30px] font-bold leading-tight text-[var(--color-brand-blue)] lg:text-[21px]">
                {{ $title }}
            </h3>

            <p class="mt-3 text-sm leading-6 text-slate-500">
                {{ $description }}
            </p>
        </div>

        <div class="pt-2">
            <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-400">
                Tuition Fee
            </p>

            <div class="mt-2 flex items-center justify-between gap-4">
                <p class="text-[16px] font-bold text-[var(--color-brand-blue)] lg:text-[18px]">
                    {{ $price }}
                </p>

                <x-ui.button class="px-5 py-2 text-xs uppercase tracking-[0.14em]">
                    {{ $buttonText }}
                </x-ui.button>
            </div>
        </div>
    </div>
</div>