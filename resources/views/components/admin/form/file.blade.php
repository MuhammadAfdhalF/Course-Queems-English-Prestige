@props([
'label',
'name',
'id' => null,
'accept' => null,
'required' => false,
'errorName' => null,
'hint' => null,
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

    <input
        id="{{ $fieldId }}"
        name="{{ $name }}"
        type="file"
        @if ($accept) accept="{{ $accept }}" @endif
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-bold file:text-slate-700 hover:file:bg-slate-200'
        ]) }}>

    @if ($hint)
    <p class="mt-2 text-xs font-medium text-slate-400">
        {{ $hint }}
    </p>
    @endif

    @error($fieldError)
    <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
    @enderror
</div>