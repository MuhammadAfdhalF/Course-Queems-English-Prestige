@props([
    'href' => '#',
    'active' => false,
])

@php
    $baseClasses = 'flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition';
    $stateClasses = $active
        ? 'bg-blue-50 text-[var(--color-brand-blue)]'
        : 'text-slate-700 hover:bg-slate-50 hover:text-[var(--color-brand-blue)]';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $stateClasses]) }}>
    <span class="shrink-0">
        {{ $icon ?? '' }}
    </span>
    <span>{{ $slot }}</span>
</a>