@props([
'title' => 'Course Title',
'level' => 'B1 Intermediate',
'progress' => 75,
'image' => 'https://placehold.co/800x500',
])

<div {{ $attributes->merge(['class' => 'group overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md']) }}>
    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
        <img src="{{ $image }}" alt="{{ $title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">

        <div class="absolute right-3 top-3">
            <span class="inline-flex items-center rounded-lg bg-[#D4A017] px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-white shadow-sm">
                {{ $level }}
            </span>
        </div>
    </div>

    <div class="space-y-3 p-4">
        <h3 class="text-lg font-bold leading-snug text-slate-900">
            {{ $title }}
        </h3>

        <div class="space-y-2">
            <div class="flex items-center justify-between text-sm text-slate-500">
                <span>Progress</span>
                <span class="font-semibold text-slate-500">{{ $progress }}%</span>
            </div>

            <div class="h-2.5 overflow-hidden rounded-full bg-slate-200">
                <div
                    class="h-full rounded-full bg-[#1847D1] transition-all duration-700 ease-out"
                    style="width: {{ $progress }}%"></div>
            </div>
        </div>
    </div>
</div>