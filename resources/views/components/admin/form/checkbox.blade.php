@props([
'label',
'name',
'id' => null,
'checked' => false,
'value' => 1,
])

@php
$fieldId = $id ?? $name;
@endphp

<label
    for="{{ $fieldId }}"
    {{ $attributes->merge([
        'class' => 'flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3'
    ]) }}>
    <input type="hidden" name="{{ $name }}" value="0">

    <input
        id="{{ $fieldId }}"
        type="checkbox"
        name="{{ $name }}"
        value="{{ $value }}"
        @checked($checked)
        class="h-5 w-5 rounded border-slate-300 text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">

    <span class="text-sm font-semibold text-slate-700">
        {{ $label }}
    </span>
</label>