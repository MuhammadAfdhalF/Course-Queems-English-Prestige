@props([
'cancelAction' => null,
'submitForm',
'submitLabel' => 'Save',
'cancelLabel' => 'Cancel',
'submitVariant' => 'primary',
])

@php
$submitClass = match ($submitVariant) {
'danger' => 'bg-rose-600 hover:bg-rose-700',
default => 'bg-[var(--color-brand-blue)] hover:opacity-90',
};
@endphp

<button
    type="button"
    @if ($cancelAction)
    @click="{{ $cancelAction }}"
    @endif
    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
    {{ $cancelLabel }}
</button>

<button
    type="submit"
    form="{{ $submitForm }}"
    class="inline-flex items-center justify-center rounded-xl px-5 py-3 text-sm font-bold text-white shadow-sm transition {{ $submitClass }}">
    {{ $submitLabel }}
</button>