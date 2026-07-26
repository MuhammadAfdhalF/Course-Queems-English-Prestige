@props([
    'variant' => 'default',
    'size' => 'md',
])

@php
$classes = match ($variant) {
    'pending', 'warning', 'waiting', 'waiting_review' => 'bg-[#AD6B10]/10 text-[#AD6B10] border border-amber-200/80',
    'completed', 'approved', 'active', 'passed', 'success' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/80',
    'primary', 'navy', 'review', 'info' => 'bg-[#080D4D]/10 text-[#080D4D] border border-indigo-200/80',
    'rejected', 'cancelled', 'inactive', 'failed', 'danger' => 'bg-rose-50 text-rose-700 border border-rose-200/80',
    default => 'bg-slate-100 text-slate-600 border border-slate-200/80',
};

$sizeClasses = match ($size) {
    'sm' => 'px-2 py-0.5 text-[10px] rounded-md font-bold',
    default => 'px-2.5 py-0.5 text-xs rounded-md font-bold',
};
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 ' . $sizeClasses . ' ' . $classes]) }}>
    {{ $slot }}
</span>