@props([
    'variant' => 'default',
    'type' => 'button',
    'title' => null,
])

@php
$classes = match ($variant) {
    'edit' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
    'delete' => 'bg-rose-50 text-rose-600 hover:bg-rose-100',
    'view' => 'bg-blue-50 text-blue-700 hover:bg-blue-100',
    'success' => 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100',
    default => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
};
@endphp

<button
    type="{{ $type }}"
    title="{{ $title }}"
    {{ $attributes->merge([
        'class' => 'inline-flex h-10 w-10 items-center justify-center rounded-xl transition ' . $classes
    ]) }}>
    {{ $slot }}
</button>