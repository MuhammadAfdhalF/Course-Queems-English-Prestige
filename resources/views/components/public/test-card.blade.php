@props([
'title' => 'Grammar',
'description' => 'Short description',
'href' => '#',
'duration' => null,
'questions' => null,
'category' => null,
'variant' => 'default',
])

@php
$isCompact = $variant === 'compact';

$cardClasses = $isCompact
? 'rounded-[22px] p-5'
: 'rounded-[24px] p-6';

$iconClasses = $isCompact
? 'h-13 w-13 rounded-2xl'
: 'h-14 w-14 rounded-2xl';

$titleClasses = $isCompact
? 'mt-5 text-lg'
: 'mt-6 text-xl';

$descriptionClasses = $isCompact
? 'mt-3 line-clamp-2 min-h-[3rem] text-sm leading-6'
: 'mt-4 line-clamp-3 min-h-[4.75rem] text-sm leading-7';

$buttonPadding = $isCompact ? 'pt-5' : 'pt-6';
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'group motion-card flex h-full flex-col border border-slate-200 bg-white text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md ' . $cardClasses
    ]) }}>

    <div class="mx-auto flex {{ $iconClasses }} items-center justify-center bg-blue-50 text-[var(--color-brand-blue)] transition-all duration-300 group-hover:bg-[#fff6da] group-hover:text-[var(--color-brand-gold)]">
        {{ $icon ?? '' }}
    </div>

    <h3 class="{{ $titleClasses }} line-clamp-2 font-bold leading-tight text-slate-900">
        {{ $title }}
    </h3>

    <p class="{{ $descriptionClasses }} text-slate-500">
        {{ $description }}
    </p>

    @if ($duration || $questions || $category)
    <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
        @if ($duration)
        <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1.5 text-[11px] font-bold text-[var(--color-brand-blue)]">
            {{ $duration }}
        </span>
        @endif

        @if ($questions)
        <span class="inline-flex items-center rounded-full bg-[#fff6da] px-3 py-1.5 text-[11px] font-bold text-[var(--color-brand-gold)]">
            {{ $questions }} Questions
        </span>
        @endif

        @if ($category)
        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-[0.08em] text-slate-700">
            {{ $category }}
        </span>
        @endif
    </div>
    @endif

    <span class="mt-auto inline-flex items-center justify-center gap-2 {{ $buttonPadding }} text-sm font-bold text-[var(--color-brand-blue)] transition group-hover:text-[var(--color-brand-gold)]">
        <span>Start Now</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="motion-link-arrow h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
        </svg>
    </span>
</a>