@props([
    'label' => null,
    'name',
    'id' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'errorName' => null,
])

@php
$fieldId = $id ?? $name;
$fieldError = $errorName ?? $name;
@endphp

<div>
    @if ($label)
    <label for="{{ $fieldId }}" class="mb-1.5 block text-xs font-bold text-slate-700">
        {{ $label }}

        @if ($required)
        <span class="text-rose-500">*</span>
        @endif
    </label>
    @endif

    <input
        id="{{ $fieldId }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full rounded-xl border border-slate-200/90 bg-white px-3.5 py-2 text-xs font-medium text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-[#080D4D] focus:ring-2 focus:ring-[#080D4D]/10 disabled:bg-slate-50 disabled:text-slate-400'
        ]) }}>

    @error($fieldError)
    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
    @enderror
</div>