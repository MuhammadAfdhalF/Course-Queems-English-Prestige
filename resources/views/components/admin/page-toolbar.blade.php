@props([
'backUrl' => null,
'backLabel' => 'Back',
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between']) }}>
    <div>
        @if ($backUrl)
        <a
            href="{{ $backUrl }}"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
            <x-admin.icon name="arrow-left" class="h-4 w-4" />
            <span>{{ $backLabel }}</span>
        </a>
        @endif
    </div>

    @isset($actions)
    <div class="flex flex-wrap gap-3 sm:justify-end">
        {{ $actions }}
    </div>
    @endisset
</div>