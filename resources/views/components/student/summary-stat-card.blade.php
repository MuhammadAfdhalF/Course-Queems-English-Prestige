@props([
'title' => 'Active Courses',
'value' => '04',
'description' => '',
'accent' => 'blue',
'icon' => 'book',
])

@php
$accentMap = [
'blue' => [
'bar' => 'bg-[#2457E6]',
'iconBg' => 'bg-blue-50',
'iconText' => 'text-[#2457E6]',
'desc' => 'text-emerald-600',
],
'orange' => [
'bar' => 'bg-orange-400',
'iconBg' => 'bg-orange-50',
'iconText' => 'text-orange-500',
'desc' => 'text-slate-400',
],
'green' => [
'bar' => 'bg-emerald-500',
'iconBg' => 'bg-emerald-50',
'iconText' => 'text-emerald-500',
'desc' => 'text-slate-400',
],
'gold' => [
'bar' => 'bg-yellow-400',
'iconBg' => 'bg-yellow-50',
'iconText' => 'text-yellow-500',
'desc' => 'text-[#D4A017]',
],
];

$style = $accentMap[$accent] ?? $accentMap['blue'];
@endphp

<div class="relative overflow-hidden rounded-[22px] border border-slate-200 bg-white p-5 shadow-sm">
    <div class="absolute inset-y-0 left-0 w-1.5 {{ $style['bar'] }}"></div>

    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-lg font-semibold text-slate-500">{{ $title }}</p>
            <p class="mt-3 text-4xl font-bold leading-none text-slate-900">{{ $value }}</p>

            @if($description)
            <p class="mt-4 text-sm font-semibold {{ $style['desc'] }}">
                {{ $description }}
            </p>
            @endif
        </div>

        <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $style['iconBg'] }} {{ $style['iconText'] }}">
            @if($icon === 'book')
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v11.494m-5.747-8.62h11.494" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 5.25h15A2.25 2.25 0 0121.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9A2.25 2.25 0 014.5 5.25z" />
            </svg>
            @elseif($icon === 'cart')
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 8L5.4 5M7 13l-1.5 3m1.5-3l1.5 3m7-3l1.5 3M9 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm10 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
            </svg>
            @elseif($icon === 'shield')
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
            </svg>
            @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @endif
        </div>
    </div>
</div>