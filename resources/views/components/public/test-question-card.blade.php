@props([
'number' => '01',
'question' => 'Question title',
])

<div {{ $attributes->merge(['class' => 'rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm lg:p-8']) }}>
    <div class="flex items-center gap-3">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#eef4ff] text-sm font-bold text-[var(--color-brand-blue)]">
            {{ $number }}
        </span>

        <h3 class="text-xl font-bold leading-tight text-slate-900 lg:text-2xl">
            {{ $question }}
        </h3>
    </div>

    <div class="mt-6">
        {{ $slot }}
    </div>
</div>