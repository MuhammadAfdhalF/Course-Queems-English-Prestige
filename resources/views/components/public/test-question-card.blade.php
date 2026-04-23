@props([
    'number' => '01',
    'question' => 'Question title',
])

<div {{ $attributes->merge(['class' => 'rounded-[20px] border border-[#e6dccb] bg-white p-7 lg:p-8']) }}>
    <div class="flex items-start gap-3">
        <span class="pt-1 text-sm font-extrabold text-[var(--color-brand-gold)]">
            {{ $number }}.
        </span>

        <div class="min-w-0 flex-1">
            <h3 class="text-[22px] font-bold leading-tight text-slate-900">
                {{ $question }}
            </h3>

            <div class="mt-5">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>