@props([
    'title' => 'Page Title',
    'description' => null,
    'eyebrow' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between']) }}>
    <div class="min-w-0">
        @if ($eyebrow || isset($breadcrumb))
            <div class="mb-1 flex items-center gap-2">
                <span class="h-1.5 w-1.5 rounded-full bg-[#AD6B10]"></span>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    {{ $eyebrow ?? $breadcrumb }}
                </p>
            </div>
        @endif

        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">
            {{ $title }}
        </h2>

        @if($description)
            <p class="mt-1 max-w-3xl text-xs sm:text-sm font-medium leading-relaxed text-slate-500">
                {{ $description }}
            </p>
        @endif
    </div>

    @isset($actions)
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            {{ $actions }}
        </div>
    @endisset
</div>