@props([
    'variant' => 'info',
])

@php
    $classes = match ($variant) {
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-700',
        'danger' => 'border-rose-200 bg-rose-50 text-rose-700',
        default => 'border-blue-200 bg-blue-50 text-blue-700',
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border px-4 py-3 text-sm font-medium ' . $classes]) }}>
    {{ $slot }}
</div>