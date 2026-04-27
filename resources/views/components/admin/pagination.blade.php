@props([
    'text' => 'Showing 1-10 of 100 records',
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-4 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between']) }}>
    <p class="font-semibold">
        {{ $text }}
    </p>

    <div class="flex items-center gap-2">
        <button
            type="button"
            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition hover:bg-slate-50">
            ‹
        </button>

        <button
            type="button"
            class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg bg-[var(--color-brand-blue)] px-3 text-sm font-bold text-white">
            1
        </button>

        <button
            type="button"
            class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg px-3 text-sm font-bold text-slate-500 transition hover:bg-slate-50">
            2
        </button>

        <button
            type="button"
            class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg px-3 text-sm font-bold text-slate-500 transition hover:bg-slate-50">
            3
        </button>

        <span class="px-2 text-slate-400">...</span>

        <button
            type="button"
            class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg px-3 text-sm font-bold text-slate-500 transition hover:bg-slate-50">
            91
        </button>

        <button
            type="button"
            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition hover:bg-slate-50">
            ›
        </button>
    </div>
</div>