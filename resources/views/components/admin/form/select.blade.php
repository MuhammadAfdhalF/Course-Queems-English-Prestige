@props([
'label',
'name',
'id' => null,
'value' => '',
'placeholder' => null,
'options' => [],
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

    <select
        id="{{ $fieldId }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100'
        ]) }}>
        @if ($placeholder)
        <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}" @selected((string) $value===(string) $optionValue)>
            {{ $optionLabel }}
        </option>
        @endforeach
    </select>

    @error($fieldError)
    <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
    @enderror
</div>