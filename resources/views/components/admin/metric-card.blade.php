@props([
'title' => 'Metric',
'value' => '0',
'description' => null,
'accent' => 'blue',
'icon' => 'users',
])

@php
$accentMap = [
'blue' => [
'value' => 'text-[#2457E6]',
'desc' => 'text-emerald-600',
'iconBg' => 'bg-blue-50',
'iconText' => 'text-[#2457E6]',
'border' => 'border-slate-200',
],
'gold' => [
'value' => 'text-[#D4A017]',
'desc' => 'text-[#D4A017]',
'iconBg' => 'bg-yellow-50',
'iconText' => 'text-[#D4A017]',
'border' => 'border-yellow-200',
],
'default' => [
'value' => 'text-slate-900',
'desc' => 'text-slate-400',
'iconBg' => 'bg-slate-100',
'iconText' => 'text-slate-500',
'border' => 'border-slate-200',
],
];

$style = $accentMap[$accent] ?? $accentMap['default'];
@endphp

<div class="rounded-[22px] border {{ $style['border'] }} bg-white p-6 shadow-sm">
    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl {{ $style['iconBg'] }} {{ $style['iconText'] }}">
        @if($icon === 'users')
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20a4 4 0 00-8 0M9 7a4 4 0 118 0 4 4 0 01-8 0M3 20a4 4 0 014-4" />
        </svg>
        @elseif($icon === 'book')
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v11.494m-5.747-8.62h11.494" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 5.25h15A2.25 2.25 0 0121.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9A2.25 2.25 0 014.5 5.25z" />
        </svg>
        @elseif($icon === 'cart')
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 8L5.4 5M7 13l-1.5 3m1.5-3l1.5 3m7-3l1.5 3M9 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm10 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
        </svg>
        @else
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
        </svg>
        @endif
    </div>

    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
        {{ $title }}
    </p>

    <p class="mt-3 text-4xl font-bold {{ $style['value'] }}">
        {{ $value }}
    </p>

    @if($description)
    <p class="mt-4 text-sm font-semibold {{ $style['desc'] }}">
        {{ $description }}
    </p>
    @endif
</div>