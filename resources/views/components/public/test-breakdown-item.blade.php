@props([
    'label' => 'Grammar',
    'value' => 85,
    'icon' => 'grammar',
])

<div class="rounded-2xl border border-slate-200 bg-white p-5">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-[var(--color-brand-gold)]">
                @if($icon === 'grammar')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 17h6M4 12h10M4 7h14" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 15l2 2 4-4" />
                    </svg>
                @elseif($icon === 'vocabulary')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.5A2.5 2.5 0 016.5 4H10v16H6.5A2.5 2.5 0 014 17.5v-11zM20 6.5A2.5 2.5 0 0017.5 4H14v16h3.5a2.5 2.5 0 002.5-2.5v-11z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 8h.01M16 8h.01" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" />
                        <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                    </svg>
                @endif
            </span>

            <span class="text-base font-bold text-slate-900">
                {{ $label }}
            </span>
        </div>

        <span class="text-sm font-bold text-slate-700">
            {{ $value }}%
        </span>
    </div>

    <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-slate-100">
        <div
            class="h-2.5 rounded-full bg-[#1f2c46]"
            style="width: {{ $value }}%;">
        </div>
    </div>
</div>