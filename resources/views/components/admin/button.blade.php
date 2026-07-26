@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
$baseClasses = 'inline-flex items-center justify-center font-bold shadow-2xs transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-[#080D4D]/20 disabled:opacity-60 disabled:cursor-not-allowed';

$variantClasses = match ($variant) {
    'primary' => 'bg-[#080D4D] text-white hover:bg-[#060A3B]',
    'secondary' => 'bg-[#AD6B10] text-white hover:bg-[#92590C]',
    'outline' => 'border border-slate-200/90 bg-white text-slate-700 hover:bg-slate-50 hover:text-[#080D4D]',
    'danger' => 'bg-rose-600 text-white hover:bg-rose-700',
    'ghost' => 'bg-transparent text-slate-600 hover:bg-slate-100 hover:text-slate-900 shadow-none',
    default => 'bg-[#080D4D] text-white hover:bg-[#060A3B]',
};

$sizeClasses = match ($size) {
    'sm' => 'h-8 px-3 text-xs rounded-lg gap-1.5',
    'lg' => 'h-10 px-5 text-sm rounded-xl gap-2.5',
    default => 'h-9 px-4 text-xs rounded-xl gap-2',
};

$classes = $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses;
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
