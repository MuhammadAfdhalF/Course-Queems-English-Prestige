@props([
'label' => '',
'name',
'id' => $name,
'value' => '',
'hint' => '',
'height' => 520,
'required' => false,
])

<div>
    @if ($label)
    <label for="{{ $id }}" class="mb-2 block text-sm font-bold text-slate-700">
        {{ $label }}

        @if ($required)
        <span class="text-rose-500">*</span>
        @endif
    </label>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        data-height="{{ $height }}"
        data-upload-url="{{ route('admin.rich-text.upload-image') }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
        'class' => 'js-admin-rich-text min-h-[360px] w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100',
    ]) }}>{!! old($name, $value) !!}</textarea>

    @if ($hint)
    <p class="mt-2 text-sm text-slate-500">
        {{ $hint }}
    </p>
    @endif

    @error($name)
    <p class="mt-2 text-sm font-medium text-rose-600">
        {{ $message }}
    </p>
    @enderror
</div>