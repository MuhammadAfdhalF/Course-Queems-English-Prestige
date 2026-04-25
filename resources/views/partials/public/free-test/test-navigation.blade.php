@props([
'previousHref' => '#',
'nextHref' => '#',
'nextLabel' => 'Next Question',
'dots' => [],
])

<div class="flex flex-col gap-5 pt-2 sm:flex-row sm:items-center sm:justify-between">
    <a
        href="{{ $previousHref }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-900 bg-white px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
        </svg>
        <span>Previous</span>
    </a>

    <div class="flex items-center justify-center gap-2">
        @foreach ($dots as $dot)
        <span class="h-2.5 w-2.5 rounded-full {{ !empty($dot['active']) ? 'bg-[var(--color-brand-gold)]' : 'bg-[#ddd5c4]' }}"></span>
        @endforeach
    </div>

    <a href="{{ $nextHref }}">
        <x-ui.button variant="gold" class="w-full px-6 py-3 sm:w-auto">
            <span class="inline-flex items-center gap-2">
                <span>{{ $nextLabel }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-6-6l6 6-6 6" />
                </svg>
            </span>
        </x-ui.button>
    </a>
</div>