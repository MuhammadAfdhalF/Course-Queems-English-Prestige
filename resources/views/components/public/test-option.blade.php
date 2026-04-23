@props([
    'label' => 'Option text',
    'selected' => false,
])

@php
    $classes = $selected
        ? 'border-slate-900 bg-[#f7edc8] text-slate-900'
        : 'border-transparent bg-[#f2f1ee] text-slate-700 hover:border-slate-300 hover:bg-[#eceae4]';
@endphp

<button
    type="button"
    {{ $attributes->merge(['class' => 'flex w-full items-center gap-3 rounded-xl border px-4 py-4 text-left text-base font-medium transition ' . $classes]) }}>
    <span class="flex h-6 w-6 items-center justify-center rounded-full border {{ $selected ? 'border-[var(--color-brand-gold)] text-[var(--color-brand-gold)]' : 'border-slate-300 text-transparent' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
        </svg>
    </span>

    <span>{{ $label }}</span>
</button>