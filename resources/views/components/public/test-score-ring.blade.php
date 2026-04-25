@props([
'score' => 78,
'maxScore' => 100,
])

<div class="relative flex h-44 w-44 items-center justify-center rounded-full border-[12px] border-[var(--color-brand-gold)] bg-white">
    <div class="text-center">
        <p class="text-5xl font-extrabold tracking-tight text-slate-900">
            {{ $score }}/{{ $maxScore }}
        </p>
        <p class="mt-2 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">
            Total Score
        </p>
    </div>
</div>