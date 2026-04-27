@props([
    'title' => 'Page Title',
    'description' => '',
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between']) }}>
    <div>
        <h2 class="text-4xl font-bold tracking-tight text-slate-900">
            {{ $title }}
        </h2>

        @if($description)
            <p class="mt-2 max-w-3xl text-base leading-7 text-slate-600">
                {{ $description }}
            </p>
        @endif
    </div>

    @isset($actions)
        <div class="flex flex-wrap gap-3">
            {{ $actions }}
        </div>
    @endisset
</div>