@props([
'type' => 'button',
'variant' => 'primary',
])

@php
$baseClasses = 'motion-button inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';

$variantClasses = match ($variant) {
'secondary' => 'bg-white text-slate-900 border border-slate-200 hover:bg-slate-50 focus:ring-slate-300',
'outline' => 'border border-slate-300 bg-transparent text-slate-900 hover:bg-slate-50 focus:ring-slate-300',
'gold' => 'bg-[var(--color-brand-gold)] text-white hover:opacity-90 focus:ring-yellow-400',
default => 'bg-[var(--color-brand-blue)] text-white hover:bg-blue-700 focus:ring-blue-300',
};
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses]) }}>
    {{ $slot }}
</button>