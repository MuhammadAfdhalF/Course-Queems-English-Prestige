@props([
'label' => 'Option',
'selected' => false,
])

@php
$classes = $selected
? 'border-[var(--color-brand-blue)] bg-blue-50 text-[var(--color-brand-blue)] shadow-sm'
: 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50';
@endphp

<button
    type="button"
    {{ $attributes->merge(['class' => 'motion-button flex w-full items-start gap-3 rounded-2xl border px-4 py-4 text-left transition-all duration-200 ' . $classes]) }}>
    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border {{ $selected ? 'border-[var(--color-brand-blue)] bg-[var(--color-brand-blue)] text-white' : 'border-slate-300 bg-white text-transparent' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M5 13l4 4L19 7" />
        </svg>
    </span>

    <span class="text-sm font-medium leading-7">
        {{ $label }}
    </span>
</button>