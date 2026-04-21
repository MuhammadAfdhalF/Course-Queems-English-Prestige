@props([
    'variant' => 'default',
])

@php
    $classes = match ($variant) {
        'success' => 'bg-emerald-100 text-emerald-700',
        'warning' => 'bg-amber-100 text-amber-700',
        'danger' => 'bg-rose-100 text-rose-700',
        'info' => 'bg-blue-100 text-blue-700',
        default => 'bg-slate-100 text-slate-700',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ' . $classes]) }}>
    {{ $slot }}
</span>