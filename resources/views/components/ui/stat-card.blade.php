@props([
'title' => 'Statistic',
'value' => '0',
'description' => null,
'valueClass' => 'text-slate-900',
'descriptionClass' => 'text-slate-500',
])

<x-ui.card {{ $attributes }}>
    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
        {{ $title }}
    </p>

    <p class="mt-4 text-4xl font-bold {{ $valueClass }}">
        {{ $value }}
    </p>

    @if($description)
    <p class="mt-3 text-sm font-semibold {{ $descriptionClass }}">
        {{ $description }}
    </p>
    @endif
</x-ui.card>