@extends('layouts.learning')

@section('content')
@php
$statusLabel = match ($attempt->status) {
'passed' => 'Passed',
'failed' => 'Failed',
'waiting_review' => 'Waiting for Review',
default => 'Submitted',
};

$statusClass = match ($attempt->status) {
'passed' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100',
'failed' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-100',
'waiting_review' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-100',
default => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
};
@endphp

<section class="mx-auto max-w-4xl space-y-6">
    <div class="reveal">
        <a href="{{ route('student.module-material', ['enrollment' => $enrollment, 'module' => $module]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Module Material
        </a>
    </div>

    <div class="reveal reveal-delay-1 overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
        <div class="px-6 py-8 text-center lg:px-10">
            <span class="inline-flex rounded-full px-4 py-2 text-xs font-extrabold uppercase tracking-[0.16em] {{ $statusClass }}">
                {{ $statusLabel }}
            </span>

            <h1 class="mt-5 text-4xl font-extrabold leading-tight text-slate-900">
                Practice Submitted
            </h1>

            <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600">
                Your practice attempt has been saved successfully.
                @if ($attempt->status === 'waiting_review')
                Some answers require manual review by admin before the final result is confirmed.
                @endif
            </p>

            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Score
                    </p>
                    <p class="mt-2 text-3xl font-extrabold text-[var(--color-brand-blue)]">
                        {{ number_format((float) $attempt->total_score, 2) }}%
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Passing Grade
                    </p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">
                        {{ $practice->passing_grade }}%
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Attempt
                    </p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">
                        #{{ $attempt->attempt_number }}
                    </p>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200 bg-slate-50 px-6 py-5 lg:px-10">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-center">
                <a
                    href="{{ route('student.module-material', ['enrollment' => $enrollment, 'module' => $module]) }}"
                    class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                    Back to Module
                </a>

                <a
                    href="{{ route('student.learning-path', $enrollment) }}"
                    class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white transition hover:opacity-95">
                    Back to Learning Path
                </a>
            </div>
        </div>
    </div>
</section>
@endsection