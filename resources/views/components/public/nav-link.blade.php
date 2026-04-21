@props([
    'href' => '#',
    'active' => false,
])

@php
    $baseClasses = 'relative inline-flex items-center px-1 py-2 text-sm font-semibold transition';
    $stateClasses = $active
        ? 'text-[var(--color-brand-blue)]'
        : 'text-slate-700 hover:text-[var(--color-brand-blue)]';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $stateClasses]) }}>
    <span>{{ $slot }}</span>

    @if($active)
        <span class="absolute -bottom-[13px] left-1/2 h-[3px] w-8 -translate-x-1/2 rounded-full bg-[var(--color-brand-gold)]"></span>
    @endif
</a>