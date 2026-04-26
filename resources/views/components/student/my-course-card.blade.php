@props([
'title' => 'Course Title',
'level' => 'B2 Intermediate',
'status' => 'active',
'statusLabel' => 'Active',
'meta' => 'Updated 2 days ago',
'progress' => 65,
'progressLabel' => 'Course Progress',
'badge' => 'GOLD LEVEL',
'image' => 'https://placehold.co/800x600',
'primaryButton' => 'Continue Learning',
'secondaryButton' => 'Chat Admin',
])

@php
$statusClasses = match ($status) {
'active' => 'border-emerald-200 bg-emerald-50 text-emerald-600',
'completed' => 'border-blue-200 bg-blue-50 text-[var(--color-brand-blue)]',
'pending' => 'border-slate-200 bg-slate-100 text-slate-500',
default => 'border-slate-200 bg-slate-100 text-slate-500',
};

$progressBarClasses = match ($status) {
'active' => 'bg-[var(--color-brand-gold)]',
'completed' => 'bg-[var(--color-brand-blue)]',
'pending' => 'bg-slate-300',
default => 'bg-[var(--color-brand-blue)]',
};

$primaryButtonClasses = match ($status) {
'active' => 'bg-[var(--color-brand-blue)] text-white hover:opacity-95',
'completed' => 'border border-[var(--color-brand-blue)] bg-white text-[var(--color-brand-blue)] hover:bg-blue-50',
'pending' => 'bg-slate-200 text-slate-500 cursor-not-allowed',
default => 'bg-[var(--color-brand-blue)] text-white',
};

$secondaryButtonClasses = match ($status) {
'active' => 'border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900',
'completed' => 'border border-slate-300 bg-white text-slate-500 hover:bg-slate-50 hover:text-slate-900',
'pending' => 'border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900',
default => 'border border-slate-300 bg-white text-slate-600',
};

$primaryHref = '#';

if ($status === 'active') {
$primaryHref = route('student.learning-path');
} elseif ($status === 'completed') {
$primaryHref = '#';
}
@endphp

<div class="group overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
    <div class="grid md:grid-cols-[248px_minmax(0,1fr)]">
        <div class="relative overflow-hidden bg-slate-100">
            @if($badge)
            <div class="absolute left-4 top-4 z-10">
                <span class="inline-flex items-center rounded-md bg-[var(--color-brand-gold)] px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-white">
                    {{ $badge }}
                </span>
            </div>
            @endif

            <img
                src="{{ $image }}"
                alt="{{ $title }}"
                class="h-full min-h-[220px] w-full object-cover transition-transform duration-500 group-hover:scale-105">
        </div>

        <div class="flex min-w-0 flex-col justify-between p-6 lg:p-7">
            <div>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0">
                        <h3 class="truncate text-[20px] font-bold leading-tight text-slate-900 lg:text-[22px]">
                            {{ $title }}
                        </h3>

                        <p class="mt-3 text-base text-slate-500">
                            {{ $level }} • {{ $meta }}
                        </p>
                    </div>

                    <div class="shrink-0">
                        <span class="inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-semibold {{ $statusClasses }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                @if($status !== 'pending')
                <div class="mt-7 space-y-2">
                    <div class="flex items-center justify-between text-[13px] font-bold uppercase tracking-[0.08em] text-slate-600">
                        <span>{{ $progressLabel }}</span>
                        <span class="text-[var(--color-brand-blue)]">{{ $progress }}%</span>
                    </div>

                    <div class="h-3 overflow-hidden rounded-full bg-slate-200">
                        <div
                            class="h-full rounded-full transition-all duration-700 ease-out {{ $progressBarClasses }}"
                            style="width: {{ $progress }}%"></div>
                    </div>
                </div>
                @else
                <div class="mt-7 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-4 text-center text-sm text-slate-500">
                    {{ $progressLabel }}
                </div>
                @endif
            </div>

            <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                @if($status === 'pending')
                <button
                    type="button"
                    class="pointer-events-none inline-flex h-12 items-center justify-center rounded-xl px-6 text-base font-bold transition {{ $primaryButtonClasses }} sm:min-w-[210px]">
                    {{ $primaryButton }}
                </button>
                @else
                <a
                    href="{{ $primaryHref }}"
                    class="inline-flex h-12 items-center justify-center rounded-xl px-6 text-base font-bold transition {{ $primaryButtonClasses }} sm:min-w-[210px]">
                    {{ $primaryButton }}
                </a>
                @endif

                <button
                    type="button"
                    class="inline-flex h-12 items-center justify-center rounded-xl px-6 text-base font-semibold transition {{ $secondaryButtonClasses }} sm:min-w-[140px]">
                    {{ $secondaryButton }}
                </button>
            </div>
        </div>
    </div>
</div>