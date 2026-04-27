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
    x-transition.opacity.duration.200ms
    class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/60 px-4 py-6 backdrop-blur-[2px]">
    <div
        x-show="{{ $model }}"
        x-transition.scale.origin.center.duration.200ms
        @click.outside="{{ $model }} = false"
        class="flex max-h-[90vh] w-full {{ $sizeClass }} flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
        {{-- Header --}}
        <div class="shrink-0 border-b border-slate-200 bg-white px-6 py-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">
                        {{ $title }}
                    </h2>

                    @if($subtitle)
                    <p class="mt-1 text-sm font-medium text-slate-500">
                        {{ $subtitle }}
                    </p>
                    @endif
                </div>

                <button
                    type="button"
                    @click="{{ $model }} = false"
                    class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
                    aria-label="Close modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="min-h-0 flex-1 overflow-y-auto px-6 py-6">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        @isset($footer)
        <div class="shrink-0 border-t border-slate-200 bg-slate-50 px-6 py-5">
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
                {{ $footer }}
            </div>
        </div>
        @endisset
    </div>
</div>