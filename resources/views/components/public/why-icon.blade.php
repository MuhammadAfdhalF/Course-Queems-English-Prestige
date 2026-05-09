@props([
'icon' => null,
])

<div {{ $attributes->merge([
    'class' => 'flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-[var(--color-brand-blue)]'
]) }}>
    @if (filled($icon))
    <img
        src="{{ asset('storage/' . $icon) }}"
        alt="Icon"
        class="h-6 w-6 object-contain"
        onerror="this.style.display='none';">
    @else
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
    </svg>
    @endif
</div>