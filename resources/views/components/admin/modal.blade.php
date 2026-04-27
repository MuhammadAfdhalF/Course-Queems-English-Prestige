@props([
    'model' => 'modalOpen',
    'title' => 'Modal Title',
    'subtitle' => '',
    'size' => 'md',
])

@php
    $sizeClass = match ($size) {
        'sm' => 'max-w-lg',
        'lg' => 'max-w-4xl',
        'xl' => 'max-w-5xl',
        default => 'max-w-2xl',
    };
@endphp

<div
    x-cloak
    x-show="{{ $model }}"
    x-transition.opacity
    class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/60 px-4 py-6"
>
    <div
        x-show="{{ $model }}"
        x-transition.scale.origin.center
        @click.outside="{{ $model }} = false"
        class="max-h-[90vh] w-full {{ $sizeClass }} overflow-hidden rounded-2xl bg-white shadow-2xl"
    >
        <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">
                    {{ $title }}
                </h2>

                @if($subtitle)
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            <button
                type="button"
                @click="{{ $model }} = false"
                class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="max-h-[calc(90vh-150px)] overflow-y-auto px-6 py-6">
            {{ $slot }}
        </div>

        @isset($footer)
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>