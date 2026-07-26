@props([
    'href' => '#',
    'active' => false,
    'title' => '',
])

@php
$baseClasses = 'group relative flex items-center rounded-xl py-2.5 text-sm font-semibold transition-all duration-200 ease-in-out';

$stateClasses = $active
    ? 'bg-indigo-50/90 text-[#080D4D] shadow-2xs font-bold'
    : 'text-slate-600 hover:bg-slate-100/80 hover:text-[#080D4D]';
@endphp

<a
    href="{{ $href }}"
    :class="desktopCollapsed ? 'justify-center px-0' : 'gap-3 px-3'"
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $stateClasses]) }}>

    {{-- Gold Accent Indicator Bar --}}
    @if($active)
        <span class="absolute left-0 top-1.5 bottom-1.5 w-1 rounded-r-full bg-[#AD6B10]"></span>
    @endif

    {{-- Icon --}}
    <span class="relative shrink-0 text-slate-500 transition-transform group-hover:scale-110 {{ $active ? 'text-[#080D4D]' : 'group-hover:text-[#080D4D]' }}">
        {{ $icon ?? '' }}
    </span>

    {{-- Label (Hidden on Desktop Collapsed) --}}
    <span
        x-show="!desktopCollapsed"
        x-cloak
        class="relative truncate transition-opacity duration-200">
        {{ $slot }}
    </span>

    {{-- Floating Tooltip for Desktop Collapsed Mode --}}
    <div
        x-show="desktopCollapsed"
        x-cloak
        class="pointer-events-none absolute left-full ml-3 z-50 hidden whitespace-nowrap rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white shadow-xl opacity-0 group-hover:opacity-100 group-hover:block transition-opacity duration-200">
        {{ $title ?: $slot }}
    </div>
</a>