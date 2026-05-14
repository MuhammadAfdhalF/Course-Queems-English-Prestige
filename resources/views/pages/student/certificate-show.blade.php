@extends('layouts.learning')

@section('content')
<style>
    @media print {
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            background: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body * {
            visibility: hidden;
        }

        .certificate-print-area,
        .certificate-print-area * {
            visibility: visible;
        }

        .certificate-print-area {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }

        .certificate-paper {
            width: 100vw !important;
            height: 100vh !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            border-width: 14px !important;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

<section class="mx-auto max-w-7xl space-y-6">
    {{-- TOP ACTIONS --}}
    <div class="no-print reveal flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ route('student.my-courses') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>
            Back to My Courses
        </a>

        <div class="flex flex-col gap-2 sm:flex-row">
            <button
                type="button"
                onclick="window.print()"
                class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                Print Certificate
            </button>

            <a
                href="{{ route('student.certificates.download', $certificate) }}"
                class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white shadow-sm transition hover:opacity-95">
                Download PDF
            </a>
        </div>
    </div>

    {{-- CERTIFICATE --}}
    <div class="certificate-print-area reveal reveal-delay-1">
        <div class="rounded-[32px] border border-slate-200 bg-white p-4 shadow-sm lg:p-6">
            <div class="certificate-paper relative mx-auto overflow-hidden rounded-[28px] border-[12px] border-[#071738] bg-[#fffdf6] px-8 py-10 text-center shadow-[0_22px_70px_rgba(15,23,42,0.14)] lg:min-h-[760px] lg:px-16 lg:py-14">
                {{-- decorative corners --}}
                <div class="pointer-events-none absolute left-5 top-5 h-24 w-24 border-l-4 border-t-4 border-[#D4A017] opacity-80"></div>
                <div class="pointer-events-none absolute right-5 top-5 h-24 w-24 border-r-4 border-t-4 border-[#D4A017] opacity-80"></div>
                <div class="pointer-events-none absolute bottom-5 left-5 h-24 w-24 border-b-4 border-l-4 border-[#D4A017] opacity-80"></div>
                <div class="pointer-events-none absolute bottom-5 right-5 h-24 w-24 border-b-4 border-r-4 border-[#D4A017] opacity-80"></div>

                {{-- soft ornaments --}}
                <div class="pointer-events-none absolute -left-28 -top-28 h-80 w-80 rounded-full border-[38px] border-[#D4A017]/10"></div>
                <div class="pointer-events-none absolute -bottom-32 -right-28 h-96 w-96 rounded-full border-[42px] border-[#071738]/10"></div>
                <div class="pointer-events-none absolute left-1/2 top-8 h-28 w-28 -translate-x-1/2 rounded-full bg-[#D4A017]/10 blur-3xl"></div>

                <div class="relative z-10 flex min-h-[680px] flex-col justify-between">
                    <div>
                        {{-- brand --}}
                        <div class="mx-auto flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white shadow-sm ring-1 ring-slate-200">
                            <img
                                src="{{ asset('images/logo-queens-english.png') }}"
                                alt="Queens English Prestige"
                                class="h-16 w-auto object-contain"
                                onerror="this.parentElement.innerHTML = '<span style=&quot;font-weight:900;color:#071738;font-size:22px;&quot;>QEP</span>';">
                        </div>

                        <p class="mt-5 text-xs font-extrabold uppercase tracking-[0.38em] text-[#D4A017]">
                            Queens English Prestige
                        </p>

                        <h1 class="mt-5 text-4xl font-black uppercase tracking-[0.18em] text-[#071738] lg:text-6xl">
                            Certificate
                        </h1>

                        <p class="mt-3 text-sm font-extrabold uppercase tracking-[0.32em] text-slate-500 lg:text-lg">
                            Of Completion
                        </p>

                        <div class="mx-auto mt-8 h-[2px] max-w-xl bg-gradient-to-r from-transparent via-[#D4A017] to-transparent"></div>

                        {{-- recipient --}}
                        <div class="mx-auto mt-9 max-w-4xl">
                            <p class="text-base leading-7 text-slate-600 lg:text-lg">
                                This certificate is proudly presented to
                            </p>

                            <h2 class="mt-4 break-words text-4xl font-black leading-tight text-slate-950 lg:text-6xl">
                                {{ $student?->name ?? 'Student Name' }}
                            </h2>

                            <div class="mx-auto mt-5 h-[2px] max-w-2xl bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>

                            <p class="mt-7 text-base leading-8 text-slate-600 lg:text-lg">
                                for successfully completing the course
                            </p>

                            <h3 class="mt-3 break-words text-3xl font-black leading-tight text-[#071738] lg:text-5xl">
                                {{ $courseLevel?->name ?? 'Course Name' }}
                            </h3>

                            <p class="mt-3 text-base font-extrabold uppercase tracking-[0.16em] text-[#D4A017] lg:text-lg">
                                {{ $courseProgram?->name ?? 'Queens English Prestige Program' }}
                            </p>
                        </div>

                        {{-- metadata --}}
                        <div class="mx-auto mt-10 grid max-w-5xl gap-3 text-left md:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200 bg-white/75 px-5 py-4 shadow-sm">
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">
                                    Certificate No.
                                </p>
                                <p class="mt-2 break-all text-sm font-black text-slate-900">
                                    {{ $certificate->certificate_number }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-white/75 px-5 py-4 shadow-sm">
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">
                                    Issued Date
                                </p>
                                <p class="mt-2 text-sm font-black text-slate-900">
                                    {{ $certificate->issued_at?->format('d F Y') ?? '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-white/75 px-5 py-4 shadow-sm">
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">
                                    Final Exam Score
                                </p>
                                <p class="mt-2 text-sm font-black text-slate-900">
                                    {{ $finalExamAttempt ? number_format((float) $finalExamAttempt->total_score, 2) . '%' : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- signatures --}}
                    <div class="mt-12">
                        <div class="grid gap-8 md:grid-cols-2 md:items-end">
                            <div class="text-center">
                                <div class="mx-auto h-[1px] max-w-[260px] bg-slate-400"></div>
                                <p class="mt-3 text-sm font-black text-slate-900">
                                    Queens English Prestige
                                </p>
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                    Authorized Signature
                                </p>
                            </div>

                            <div class="text-center">
                                <div class="mx-auto h-[1px] max-w-[260px] bg-slate-400"></div>
                                <p class="mt-3 text-sm font-black text-slate-900">
                                    {{ $student?->name ?? 'Student' }}
                                </p>
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                    Certificate Holder
                                </p>
                            </div>
                        </div>

                        <p class="mx-auto mt-8 max-w-3xl text-xs leading-6 text-slate-500">
                            This certificate verifies that the student has completed the required learning activities and passed the final assessment according to Queens English Prestige standards.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER ACTIONS --}}
    <div class="no-print reveal reveal-delay-2 rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900">
                    Certificate Available
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Your certificate has been issued. You can print it or download the official PDF file. </p>
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

                <a
                    href="{{ route('student.certificates.download', $certificate) }}"
                    class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white transition hover:opacity-95">
                    Download PDF
                </a>
            </div>
        </div>
    </div>
</section>
@endsection