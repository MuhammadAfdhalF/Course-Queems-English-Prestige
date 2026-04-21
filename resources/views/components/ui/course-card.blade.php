@props([
'title' => 'Course Title',
'level' => 'Beginner',
'mode' => 'Online',
'price' => 'Rp 0',
'progress' => null,
'image' => 'https://placehold.co/600x400',
'buttonText' => 'View Detail',
])

<x-ui.card {{ $attributes->merge(['class' => 'overflow-hidden p-0']) }}>
    <div class="aspect-[4/3] overflow-hidden bg-slate-100">
        <img src="{{ $image }}" alt="{{ $title }}" class="h-full w-full object-cover">
    </div>

    <div class="space-y-4 p-5">
        <div class="flex flex-wrap gap-2">
            <x-ui.badge variant="warning">{{ $level }}</x-ui.badge>
            <x-ui.badge variant="success">{{ $mode }}</x-ui.badge>
        </div>

        <div class="flex items-start justify-between gap-3">
            <h3 class="text-xl font-bold leading-snug text-slate-900">{{ $title }}</h3>
            <p class="whitespace-nowrap text-sm font-semibold text-[var(--color-brand-blue)]">
                {{ $price }}
            </p>
        </div>

        @if(!is_null($progress))
        <div class="space-y-2">
            <div class="flex items-center justify-between text-sm text-slate-500">
                <span>Progress</span>
                <span>{{ $progress }}%</span>
            </div>

            <div class="h-2 overflow-hidden rounded-full bg-slate-200">
                <div
                    class="h-full rounded-full bg-[var(--color-brand-blue)]"
                    style="width: {{ $progress }}%"></div>
            </div>
        </div>
        @endif

        <x-ui.button variant="outline" class="w-full">
            {{ $buttonText }}
        </x-ui.button>
    </div>
</x-ui.card>