@props([
'label',
'name',
'id' => null,
'value' => '',
'placeholder' => '',
'rows' => 5,
'required' => false,
'errorName' => null,
])

@php
$fieldId = $id ?? $name;
$fieldError = $errorName ?? $name;
@endphp

<div>
    <label for="{{ $fieldId }}" class="mb-2 block text-sm font-bold text-slate-700">
        {{ $label }}

        @if ($required)
        <span class="text-rose-500">*</span>
        @endif
    </label>

    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100'
        ]) }}>{{ $value }}</textarea>

    @error($fieldError)
    <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
    @enderror
</div>