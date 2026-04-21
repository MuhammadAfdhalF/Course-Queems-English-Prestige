@props([
'title' => 'Course Title',
'level' => 'B1 Intermediate',
'progress' => 75,
'image' => 'https://placehold.co/800x500',
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-sm']) }}>
    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
        <img src="{{ $image }}" alt="{{ $title }}" class="h-full w-full object-cover">

        <div class="absolute right-3 top-3">
            <span class="inline-flex items-center rounded-lg bg-[#D4A017] px-3 py-1 text-xs font-bold uppercase tracking-[0.12em] text-white shadow-sm">
                {{ $level }}
            </span>
        </div>
    </div>

    <div class="space-y-4 p-5">
        <h3 class="text-[30px] font-bold leading-tight text-slate-900">
            {{ $title }}
        </h3>

        <div class="space-y-2">
            <div class="flex items-center justify-between text-base text-slate-500">
                <span>Progress</span>
                <span class="font-semibold text-slate-500">{{ $progress }}%</span>
            </div>

            <div class="h-3 overflow-hidden rounded-full bg-slate-200">
                <div
                    class="h-full rounded-full bg-[#1847D1]"
                    style="width: {{ $progress }}%"></div>
            </div>
        </div>
    </div>
</div>