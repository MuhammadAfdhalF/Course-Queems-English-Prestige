@props([
'number' => '01',
'title' => 'Module Title',
'status' => 'completed', // completed | current | locked
'buttonText' => 'Review',
'note' => '',
])

@php
$wrapperClasses = match ($status) {
'completed' => 'border-slate-200 bg-white',
'current' => 'border-[var(--color-brand-blue)] bg-white ring-2 ring-blue-100',
'locked' => 'border-slate-200 bg-slate-50 opacity-70',
default => 'border-slate-200 bg-white',
};

$iconWrapperClasses = match ($status) {
'completed' => 'bg-emerald-100 text-emerald-600',
'current' => 'bg-blue-100 text-[var(--color-brand-blue)]',
'locked' => 'bg-slate-100 text-slate-400',
default => 'bg-slate-100 text-slate-500',
};

$label = match ($status) {
'completed' => 'Completed',
'current' => 'Current',
'locked' => '',
default => '',
};

$labelClasses = match ($status) {
'completed' => 'bg-emerald-50 text-emerald-600',
'current' => 'bg-blue-50 text-[var(--color-brand-blue)]',
default => '',
};

$buttonClasses = match ($status) {
'completed' => 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50',
'current' => 'bg-[var(--color-brand-blue)] text-white hover:opacity-95',
'locked' => 'bg-slate-100 text-slate-400 cursor-not-allowed',
default => 'border border-slate-300 bg-white text-slate-700',
};
@endphp

<div class="rounded-[20px] border px-5 py-5 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md {{ $wrapperClasses }}">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex min-w-0 items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full {{ $iconWrapperClasses }}">
                @if($status === 'completed')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12l2.5 2.5L16 9" />
                </svg>
                @elseif($status === 'current')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                    <circle cx="12" cy="12" r="3.5" stroke-width="1.8" />
                </svg>
                @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="6" y="10" width="12" height="10" rx="2" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10V8a4 4 0 118 0v2" />
                </svg>
                @endif
            </div>

            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-bold uppercase tracking-[0.14em] text-slate-400">
                        Module {{ $number }}
                    </span>

                    @if($label)
                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-[0.08em] {{ $labelClasses }}">
                        {{ $label }}
                    </span>
                    @endif
                </div>

                <h3 class="mt-2 text-[18px] font-bold leading-tight {{ $status === 'completed' ? 'text-slate-400 line-through' : ($status === 'locked' ? 'text-slate-400' : 'text-slate-900') }}">
                    {{ $title }}
                </h3>

                @if($note)
                <p class="mt-1 text-sm text-slate-400">
                    {{ $note }}
                </p>
                @endif
            </div>
        </div>

        <div class="shrink-0">
            @if($status === 'locked')
            <button
                type="button"
                class="pointer-events-none inline-flex h-12 min-w-[130px] items-center justify-center rounded-xl px-6 text-base font-bold transition {{ $buttonClasses }}">
                {{ $buttonText }}
            </button>
            @else
            <a
                href="#"
                class="inline-flex h-12 min-w-[130px] items-center justify-center gap-2 rounded-xl px-6 text-base font-bold transition {{ $buttonClasses }}">
                {{ $buttonText }}

                @if($status === 'current')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M13 5l7 7-7 7" />
                </svg>
                @endif
            </a>
            @endif
        </div>
    </div>
</div>