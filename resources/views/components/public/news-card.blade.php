@props([
'title' => 'News Title',
'category' => 'Education Tips',
'date' => 'Oct 18, 2023',
'excerpt' => 'Short article summary goes here.',
'image' => 'https://placehold.co/800x500/e8eef8/1e293b?text=News',
'href' => '#',
])

<div {{ $attributes->merge(['class' => 'group motion-card overflow-hidden rounded-[18px] border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md']) }}>
    <a href="{{ $href }}" class="block">
        <div class="aspect-[4/2.35] overflow-hidden bg-slate-100">
            <img
                src="{{ $image }}"
                alt="{{ $title }}"
                class="motion-image h-full w-full object-cover">
        </div>
    </a>

    <div class="p-5">
        <div class="flex items-center justify-between gap-3">
            <span class="inline-flex items-center rounded-md bg-[#f8efcf] px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-[var(--color-brand-gold)]">
                {{ $category }}
            </span>

            <span class="text-xs text-slate-400">
                {{ $date }}
            </span>
        </div>

        <div class="mt-4">
            <a href="{{ $href }}">
                <h3 class="text-[22px] font-bold leading-tight text-slate-900 transition hover:text-[var(--color-brand-blue)]">
                    {{ $title }}
                </h3>
            </a>

            <p class="mt-4 text-sm leading-7 text-slate-500">
                {{ $excerpt }}
            </p>
        </div>

        <div class="mt-5">
            <a
                href="{{ $href }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-[#2457E6]">
                <span>Read More</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="motion-link-arrow h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M13 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>