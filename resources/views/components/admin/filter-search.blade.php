@props([
    'placeholder' => 'Search...',
])

<div class="flex h-13 items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 transition focus-within:border-[var(--color-brand-blue)] focus-within:ring-2 focus-within:ring-blue-100">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
    </svg>

    <input
        type="text"
        placeholder="{{ $placeholder }}"
        {{ $attributes->whereStartsWith('x-') }}
        class="w-full bg-transparent text-sm font-medium text-slate-700 outline-none placeholder:text-slate-400">
</div>