@props([
'title' => 'Course Title',
'level' => 'Course Program',
'mode' => 'Online',
'price' => 'Rp 0',
'description' => 'Short description about this course.',
'image' => 'https://placehold.co/800x500',
'href' => '#',
'buttonText' => 'View Detail',
'statusLabel' => 'Available',
'statusClass' => 'bg-emerald-50 text-emerald-700',
'buttonClass' => 'bg-[var(--color-brand-blue)] text-white hover:opacity-90',
'disabled' => false,
])

@php
$modeClasses = match ($mode) {
'Offline' => 'bg-[#CFE2D7] text-[var(--color-brand-blue)]',
'Hybrid' => 'bg-yellow-100 text-[var(--color-brand-blue)]',
default => 'bg-[#58E19A] text-[var(--color-brand-blue)]',
};
@endphp

<div {{ $attributes->merge(['class' => 'group motion-card overflow-hidden rounded-[18px] border border-slate-200 bg-white shadow-sm']) }}>
    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
        <img
            src="{{ $image }}"
            alt="{{ $title }}"
            class="motion-image h-full w-full object-cover">

        <div class="absolute left-3 top-3">
            <span class="inline-flex items-center rounded-md px-3 py-1 text-[10px] font-bold uppercase tracking-[0.12em] {{ $modeClasses }}">
                {{ $mode }}
            </span>
        </div>

        <div class="absolute right-3 top-3">
            <span class="inline-flex items-center rounded-md px-3 py-1 text-[10px] font-bold uppercase tracking-[0.12em] {{ $statusClass }}">
                {{ $statusLabel }}
            </span>
        </div>
    </div>

    <div class="space-y-4 p-5">
        <div>
            <p class="mb-2 text-[10px] font-black uppercase tracking-[0.16em] text-[var(--color-brand-gold)]">
                {{ $level }}
            </p>

            <h3 class="text-[26px] font-bold leading-tight text-[var(--color-brand-blue)] lg:text-[21px]">
                {{ $title }}
            </h3>

            <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-500">
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

                @if ($disabled)
                <button
                    type="button"
                    disabled
                    class="inline-flex cursor-not-allowed items-center justify-center rounded-xl px-5 py-2 text-xs font-semibold uppercase tracking-[0.14em] {{ $buttonClass }}">
                    {{ $buttonText }}
                </button>
                @else
                <a
                    href="{{ $href }}"
                    class="inline-flex items-center justify-center rounded-xl px-5 py-2 text-xs font-semibold uppercase tracking-[0.14em] transition {{ $buttonClass }}">
                    {{ $buttonText }}
                </a>
                @endif
            </div>
        </div>
    </div>
</div>