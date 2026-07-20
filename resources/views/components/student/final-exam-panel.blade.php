@props([
'title' => 'Course Final Exam',
'description' => null,
'passingGrade' => 70,
'maxAttempts' => null,
'isUnlocked' => false,
'latestAttempt' => null,
'canRetake' => false,
'startHref' => '#',
'resultHref' => null,
])

@php
$attemptStatusLabel = match ($latestAttempt?->status) {
'passed' => 'Passed',
'failed' => 'Failed',
'waiting_review' => 'Waiting for Review',
default => null,
};

$attemptStatusClass = match ($latestAttempt?->status) {
'passed' => 'bg-emerald-400/15 text-emerald-200 ring-1 ring-emerald-300/20',
'failed' => 'bg-rose-400/15 text-rose-200 ring-1 ring-rose-300/20',
'waiting_review' => 'bg-amber-400/15 text-amber-200 ring-1 ring-amber-300/20',
default => 'bg-white/10 text-slate-200 ring-1 ring-white/10',
};

$isAttemptLimitReached = $latestAttempt && ! $canRetake;
@endphp

<div class="relative overflow-hidden rounded-[26px] bg-gradient-to-r from-[#071738] via-[#081B4A] to-[#0A2059] px-7 py-8 text-white shadow-[0_18px_45px_rgba(15,23,42,0.18)]">
    <div class="relative z-10 flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
        <div class="max-w-2xl">
            <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-slate-200">
                Certification Milestone
            </span>

            <h3 class="mt-6 text-[38px] font-bold leading-tight text-white">
                {{ $title }}
            </h3>

            <div class="rich-text-content rich-text-content-dark mt-4 max-w-2xl text-base leading-7">
                {!! $description ?: '<p>This comprehensive exam covers all materials from this course. Your certificate will be processed after passing the required assessment.</p>' !!}
            </div>

            <div class="mt-5 flex flex-wrap items-center gap-2">
                <span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-[var(--color-brand-gold)] ring-1 ring-white/10">
                    Passing grade: {{ $passingGrade }}%
                </span>

                @if ($maxAttempts)
                <span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-slate-200 ring-1 ring-white/10">
                    Max attempts: {{ $maxAttempts }}
                </span>
                @endif

                @if ($latestAttempt)
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $attemptStatusClass }}">
                    {{ $attemptStatusLabel }} · Score {{ number_format((float) $latestAttempt->total_score, 2) }}%
                </span>
                @endif
            </div>

            @if (! $isUnlocked)
            <p class="mt-5 text-sm font-semibold leading-6 text-blue-100">
                Complete all modules to unlock the final exam.
            </p>
            @elseif ($latestAttempt?->status === 'waiting_review')
            <p class="mt-5 text-sm font-semibold leading-6 text-amber-100">
                Your final exam is waiting for admin review. The course will be completed after your final result is passed.
            </p>
            @elseif ($latestAttempt?->status === 'passed')
            <p class="mt-5 text-sm font-semibold leading-6 text-emerald-100">
                You passed the final exam. Your course completion record has been updated.
            </p>
            @elseif ($isAttemptLimitReached)
            <p class="mt-5 text-sm font-semibold leading-6 text-slate-200">
                You have reached the maximum number of attempts for this final exam.
            </p>
            @endif
        </div>

        <div class="flex shrink-0 flex-col gap-3">
            @if (! $isUnlocked)
            <button
                type="button"
                class="inline-flex h-14 min-w-[240px] cursor-not-allowed items-center justify-center gap-3 rounded-2xl bg-white/70 px-8 text-lg font-bold text-slate-400 shadow-lg">
                Locked
            </button>
            @elseif ($latestAttempt)
            @if ($resultHref)
            <a
                href="{{ $resultHref }}"
                class="inline-flex h-14 min-w-[240px] items-center justify-center gap-3 rounded-2xl bg-white px-8 text-lg font-bold text-[var(--color-brand-blue)] shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                Review Result
            </a>
            @endif

            @if ($canRetake && $latestAttempt->status !== 'passed')
            <a
                href="{{ $startHref }}"
                class="inline-flex h-14 min-w-[240px] items-center justify-center gap-3 rounded-2xl bg-[var(--color-brand-gold)] px-8 text-lg font-bold text-white shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                Retake Final Exam
            </a>
            @endif
            @else
            <a
                href="{{ $startHref }}"
                class="inline-flex h-14 min-w-[240px] items-center justify-center gap-3 rounded-2xl bg-white px-8 text-lg font-bold text-[var(--color-brand-blue)] shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                Start Final Exam
            </a>
            @endif
        </div>
    </div>

    <div class="pointer-events-none absolute -right-10 top-8 h-40 w-40 rotate-12 rounded-[32px] border-[14px] border-white/10"></div>
</div>