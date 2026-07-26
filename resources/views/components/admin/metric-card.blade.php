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
        'value' => 'text-[#080D4D]',
        'desc' => 'text-slate-500',
        'iconBg' => 'bg-[#080D4D]/5',
        'iconText' => 'text-[#080D4D]',
        'border' => 'border-slate-200/90 hover:border-slate-300',
    ],
    'gold' => [
        'value' => 'text-[#AD6B10]',
        'desc' => 'text-[#AD6B10]',
        'iconBg' => 'bg-[#AD6B10]/10',
        'iconText' => 'text-[#AD6B10]',
        'border' => 'border-amber-200/80 hover:border-amber-300',
    ],
    'default' => [
        'value' => 'text-slate-900',
        'desc' => 'text-slate-500',
        'iconBg' => 'bg-slate-100',
        'iconText' => 'text-slate-600',
        'border' => 'border-slate-200/90 hover:border-slate-300',
    ],
];

$style = $accentMap[$accent] ?? $accentMap['default'];
@endphp

<div class="rounded-xl border {{ $style['border'] }} bg-white p-4 shadow-2xs transition-all duration-200 hover:shadow-xs">
    <div class="flex items-center justify-between gap-2">
        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 truncate">
            {{ $title }}
        </p>

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $style['iconBg'] }} {{ $style['iconText'] }}">
            @if($icon === 'users')
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20a4 4 0 00-8 0M9 7a4 4 0 118 0 4 4 0 01-8 0M3 20a4 4 0 014-4" />
            </svg>
            @elseif($icon === 'book')
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            @elseif($icon === 'cart')
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            @endif
        </div>
    </div>

    <p class="mt-2 text-2xl font-black tracking-tight {{ $style['value'] }}">
        {{ $value }}
    </p>

    @if($description)
    <p class="mt-1 text-[11px] font-medium text-slate-400 truncate">
        {{ $description }}
    </p>
    @endif
</div>