@props([
'href' => '#',
'active' => false,
])

@php
$baseClasses = 'group relative flex items-center gap-3 overflow-hidden rounded-xl px-4 py-3 text-sm font-semibold transition';

$stateClasses = $active
? 'bg-blue-50 text-[var(--color-brand-blue)] shadow-sm'
: 'text-slate-600 hover:bg-slate-50 hover:text-[var(--color-brand-blue)]';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $stateClasses]) }}>
    @if($active)
    <span class="absolute inset-y-2 left-0 w-1 rounded-r-full bg-[var(--color-brand-gold)]"></span>
    @endif

    <span class="relative shrink-0 transition group-hover:scale-105">
        {{ $icon ?? '' }}
    </span>

    <span class="relative truncate">
        {{ $slot }}
    </span>
</a>