@props([
    'placeholder' => 'Type your answer...',
    'helper' => null,
])

<div>
    <input
        type="text"
        placeholder="{{ $placeholder }}"
        class="w-full rounded-xl border border-[#ddd5c4] bg-[#f7f6f2] px-4 py-3.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[var(--color-brand-gold)] focus:outline-none focus:ring-2 focus:ring-yellow-100" />

    @if ($helper)
        <p class="mt-3 flex items-center gap-2 text-xs text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8h.01M11 11h1v5h1" />
            </svg>
            <span>{{ $helper }}</span>
        </p>
    @endif
</div>