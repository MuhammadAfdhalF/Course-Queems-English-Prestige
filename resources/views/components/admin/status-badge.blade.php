@props([
'variant' => 'default',
])

@php
$classes = match ($variant) {
'pending' => 'bg-yellow-100 text-yellow-700',
'completed' => 'bg-emerald-100 text-emerald-700',
'approved' => 'bg-blue-100 text-blue-700',
'rejected' => 'bg-rose-100 text-rose-700',
default => 'bg-slate-100 text-slate-700',
};
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ' . $classes]) }}>
    {{ $slot }}
</span>