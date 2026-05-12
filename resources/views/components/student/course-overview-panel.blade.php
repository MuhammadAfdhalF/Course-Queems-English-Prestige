@props([
'description' => null,
'learningMode' => 'online',
'accessType' => 'lifetime',
'accessDurationDays' => null,
'modulesCount' => 0,
'hasFinalExam' => false,
])

@php
$learningModeLabel = match ($learningMode) {
'offline' => 'Offline',
'hybrid' => 'Hybrid',
default => 'Online',
};

$accessLabel = $accessType === 'limited'
? ($accessDurationDays . ' days access')
: 'Lifetime access';
@endphp

<div x-data="{ open: true }" class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
    <button
        type="button"
        @click="open = !open"
        class="flex w-full items-center justify-between px-6 py-5 text-left lg:px-8">
        <div class="flex items-center gap-3">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8h.01M11 12h1v4h1" />
                </svg>
            </span>

            <h2 class="text-2xl font-bold text-slate-900">
                Course Overview
            </h2>
        </div>

        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-transition class="border-t border-slate-200 px-6 py-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
            <div>
                <h3 class="text-lg font-bold text-slate-900">
                    About This Course
                </h3>

                <div class="rich-text-content mt-3 text-slate-600">
                    {!! $description ?: '<p>Course overview will be available soon.</p>' !!}
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Learning Mode</p>
                    <p class="mt-2 text-lg font-extrabold text-slate-900">{{ $learningModeLabel }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Access</p>
                    <p class="mt-2 text-lg font-extrabold text-slate-900">{{ $accessLabel }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Modules</p>
                    <p class="mt-2 text-lg font-extrabold text-slate-900">{{ $modulesCount }} modules</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Final Exam</p>
                    <p class="mt-2 text-lg font-extrabold text-slate-900">
                        {{ $hasFinalExam ? 'Available' : 'Not available yet' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>