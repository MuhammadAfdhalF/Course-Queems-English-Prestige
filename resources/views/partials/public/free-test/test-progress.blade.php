@props([
    'progress' => 10,
])

<section class="bg-[#f7f6f2]">
    <div class="h-1.5 w-full bg-[#e7e1d3]">
        <div
            class="h-1.5 bg-[var(--color-brand-gold)] transition-all duration-300"
            style="width: {{ $progress }}%;">
        </div>
    </div>
</section>