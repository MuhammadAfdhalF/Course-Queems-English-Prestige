@extends('layouts.learning')

@section('content')
<section class="mx-auto max-w-6xl space-y-6">
    <div class="reveal flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ route('student.my-courses') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>
            Back to My Courses
        </a>

        <button
            type="button"
            onclick="window.print()"
            class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Print Certificate
        </button>
    </div>

    <div class="reveal reveal-delay-1 overflow-hidden rounded-[32px] border border-slate-200 bg-white p-4 shadow-sm lg:p-6">
        <div class="relative overflow-hidden rounded-[26px] border-[10px] border-[#080D4D] bg-[#fbfaf5] px-8 py-10 text-center lg:px-14 lg:py-14">
            <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full border-[34px] border-[#AD6B10]/10"></div>
            <div class="pointer-events-none absolute -bottom-28 -right-24 h-80 w-80 rounded-full border-[38px] border-[#080D4D]/10"></div>

            <div class="relative z-10">
                <div class="mx-auto flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white shadow-sm ring-1 ring-slate-200">
                    <img
                        src="{{ asset('images/logo-queens-english.png') }}"
                        alt="Queens English Prestige"
                        class="h-16 w-auto object-contain"
                        onerror="this.style.display='none';">
                </div>

                <p class="mt-6 text-xs font-extrabold uppercase tracking-[0.35em] text-[#AD6B10]">
                    Queens English Prestige
                </p>

                <h1 class="mt-5 text-4xl font-extrabold uppercase tracking-[0.16em] text-[#080D4D] lg:text-6xl">
                    Certificate
                </h1>

                <p class="mt-3 text-lg font-semibold uppercase tracking-[0.28em] text-slate-500">
                    Of Completion
                </p>

                <div class="mx-auto mt-10 max-w-3xl">
                    <p class="text-base leading-7 text-slate-600">
                        This certificate is proudly presented to
                    </p>

                    <h2 class="mt-4 text-4xl font-extrabold leading-tight text-slate-900 lg:text-5xl">
                        {{ $student?->name ?? 'Student Name' }}
                    </h2>

                    <div class="mx-auto mt-5 h-[2px] max-w-xl bg-gradient-to-r from-transparent via-[#AD6B10] to-transparent"></div>

                    <p class="mt-7 text-base leading-8 text-slate-600">
                        for successfully completing the course
                    </p>

                    <h3 class="mt-3 text-3xl font-extrabold leading-tight text-[#080D4D] lg:text-4xl">
                        {{ $courseLevel?->name ?? 'Course Name' }}
                    </h3>

                    <p class="mt-3 text-lg font-semibold text-[#AD6B10]">
                        {{ $courseProgram?->name ?? 'Queens English Prestige Program' }}
                    </p>
                </div>

                <div class="mx-auto mt-10 grid max-w-4xl gap-4 text-left md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-white/70 px-5 py-4">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-slate-400">
                            Certificate No.
                        </p>
                        <p class="mt-2 break-all text-sm font-extrabold text-slate-900">
                            {{ $certificate->certificate_number }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white/70 px-5 py-4">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-slate-400">
                            Issued Date
                        </p>
                        <p class="mt-2 text-sm font-extrabold text-slate-900">
                            {{ $certificate->issued_at?->format('d F Y') ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white/70 px-5 py-4">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-slate-400">
                            Final Exam Score
                        </p>
                        <p class="mt-2 text-sm font-extrabold text-slate-900">
                            {{ $finalExamAttempt ? number_format((float) $finalExamAttempt->total_score, 2) . '%' : '-' }}
                        </p>
                    </div>
                </div>

                <div class="mt-12 grid gap-8 md:grid-cols-2 md:items-end">
                    <div class="text-center">
                        <div class="mx-auto h-[1px] max-w-[260px] bg-slate-400"></div>
                        <p class="mt-3 text-sm font-extrabold text-slate-900">
                            Queens English Prestige
                        </p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                            Authorized Signature
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="mx-auto h-[1px] max-w-[260px] bg-slate-400"></div>
                        <p class="mt-3 text-sm font-extrabold text-slate-900">
                            {{ $student?->name ?? 'Student' }}
                        </p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                            Certificate Holder
                        </p>
                    </div>
                </div>

                <p class="mx-auto mt-10 max-w-3xl text-xs leading-6 text-slate-500">
                    This certificate verifies that the student has completed the required learning activities and passed the final assessment according to Queens English Prestige standards.
                </p>
            </div>
        </div>
    </div>

    <div class="reveal reveal-delay-2 rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900">
                    Certificate Available
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Your certificate has been issued. PDF download will be available in the next update.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a
                    href="{{ route('student.my-courses') }}"
                    class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                    My Courses
                </a>

                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white transition hover:opacity-95">
                    Print Certificate
                </button>
            </div>
        </div>
    </div>
</section>
@endsection