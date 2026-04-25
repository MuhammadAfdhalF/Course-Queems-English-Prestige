@props([
'progress' => 30,
])

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 pb-6 lg:px-8">
        <div class="reveal">
            <div class="mb-3 flex items-center justify-between text-sm font-medium text-slate-500">
                <span>Progress</span>
                <span>{{ $progress }}%</span>
            </div>

            <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                <div
                    class="h-3 rounded-full bg-[var(--color-brand-blue)] transition-all duration-500 ease-out"
                    style="width: {{ $progress }}%;">
                </div>
            </div>
        </div>
    </div>
</section>