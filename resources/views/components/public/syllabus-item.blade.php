@props([
    'title' => 'Module Title',
    'content' => 'Module description.',
    'open' => false,
])

<div x-data="{ open: @js($open) }" class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <button
        type="button"
        @click="open = !open"
        class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left"
    >
        <span class="text-lg font-bold text-[#2457E6]">
            {{ $title }}
        </span>

        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-slate-400 transition-transform"
            :class="open ? 'rotate-180' : ''"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-transition class="border-t border-slate-200 px-5 pb-5 pt-4">
        <p class="text-sm leading-7 text-slate-600">
            {{ $content }}
        </p>
    </div>
</div>