@extends('layouts.learning')

@section('content')
@php
$templateBackground = $certificate->certificateTemplate?->background_image;
$templateBackgroundUrl = $templateBackground
? asset('storage/' . $templateBackground)
: null;

$signatureImage = $certificateSetting?->signature_image;
$signatureImageUrl = $signatureImage
? asset('storage/' . $signatureImage)
: null;

$signerName = $certificateSetting?->signerName() ?? 'Queens English Prestige';
$signerTitle = $certificateSetting?->signerTitle() ?? 'Authorized Signature';

$verificationUrl = $certificate->verification_token
? route('certificates.verify', $certificate->verification_token)
: null;

$qrSvg = $verificationUrl
? \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(110)->margin(1)->generate($verificationUrl)
: null;
@endphp

<style>
    .certificate-preview-frame {
        aspect-ratio: 297 / 210;
    }

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

        .certificate-preview-frame {
            width: 100vw !important;
            height: 100vh !important;
            aspect-ratio: auto !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

<section class="mx-auto max-w-7xl space-y-6">
    {{-- TOP ACTIONS --}}
    <div class="no-print reveal flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <a
            href="{{ route('student.my-courses') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
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
        <div class="rounded-[32px] border border-slate-200 bg-white p-3 shadow-sm lg:p-5">
            <div
                class="certificate-preview-frame relative mx-auto w-full overflow-hidden rounded-[26px] bg-[#fffdf6] text-center shadow-[0_22px_70px_rgba(15,23,42,0.14)]">

                @if ($templateBackgroundUrl)
                <img
                    src="{{ $templateBackgroundUrl }}"
                    alt="Certificate Template Background"
                    class="absolute inset-0 h-full w-full">
                @else
                <div class="absolute inset-[2.8%] border-[10px] border-[#071738]"></div>
                <div class="absolute inset-[6%] border border-[#D4A017]/40"></div>

                <div class="pointer-events-none absolute left-[6.5%] top-[7%] h-[14%] w-[10%] border-l-4 border-t-4 border-[#D4A017] opacity-80"></div>
                <div class="pointer-events-none absolute right-[6.5%] top-[7%] h-[14%] w-[10%] border-r-4 border-t-4 border-[#D4A017] opacity-80"></div>
                <div class="pointer-events-none absolute bottom-[7%] left-[6.5%] h-[14%] w-[10%] border-b-4 border-l-4 border-[#D4A017] opacity-80"></div>
                <div class="pointer-events-none absolute bottom-[7%] right-[6.5%] h-[14%] w-[10%] border-b-4 border-r-4 border-[#D4A017] opacity-80"></div>

                <div class="pointer-events-none absolute -left-[12%] top-[20%] h-[42%] w-[30%] rounded-full border-[36px] border-[#D4A017]/10"></div>
                <div class="pointer-events-none absolute -bottom-[10%] -right-[10%] h-[48%] w-[34%] rounded-full border-[42px] border-[#071738]/10"></div>
                @endif

                <div class="absolute inset-0 z-10">
                    {{-- HEADER --}}
                    <div class="absolute left-[10%] right-[10%] top-[8%] text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center overflow-hidden rounded-full border border-slate-200 bg-white shadow-sm lg:h-20 lg:w-20">
                            <img
                                src="{{ asset('images/logo-queens-english.png') }}"
                                alt="Queens English Prestige"
                                class="h-12 w-auto object-contain lg:h-16"
                                onerror="this.parentElement.innerHTML = '<span style=&quot;font-weight:900;color:#071738;font-size:20px;&quot;>QEP</span>';">
                        </div>

                        <p class="mt-3 text-[9px] font-black uppercase tracking-[0.34em] text-[#D4A017] lg:text-xs">
                            Queens English Prestige
                        </p>

                        <h1 class="mt-4 text-4xl font-black uppercase leading-none tracking-[0.18em] text-[#071738] md:text-5xl lg:text-6xl">
                            Certificate
                        </h1>

                        <p class="mt-3 text-[10px] font-black uppercase tracking-[0.32em] text-slate-500 lg:text-sm">
                            Of Completion
                        </p>

                        <div class="mx-auto mt-4 h-[2px] max-w-[330px] bg-[#D4A017] lg:max-w-[360px]"></div>
                    </div>

                    {{-- RECIPIENT --}}
                    <div class="absolute left-[12%] right-[12%] top-[37%] text-center">
                        <p class="text-xs font-medium text-slate-600 lg:text-base">
                            This certificate is proudly presented to
                        </p>

                        <h2 class="mt-3 break-words text-3xl font-black leading-tight text-slate-950 lg:text-5xl">
                            {{ $student?->name ?? 'Student Name' }}
                        </h2>

                        <div class="mx-auto mt-4 h-px max-w-[520px] bg-slate-300"></div>

                        <p class="mt-5 text-xs font-medium text-slate-600 lg:text-base">
                            for successfully completing the course
                        </p>

                        <h3 class="mt-2 break-words text-2xl font-black leading-tight text-[#071738] lg:text-4xl">
                            {{ $courseLevel?->name ?? 'Course Name' }}
                        </h3>

                        <p class="mt-2 text-[10px] font-black uppercase tracking-[0.16em] text-[#D4A017] lg:text-sm">
                            {{ $courseProgram?->name ?? 'Queens English Prestige Program' }}
                        </p>
                    </div>

                    {{-- METADATA --}}
                    <div class="absolute left-[24%] right-[24%] top-[67%] grid grid-cols-2 gap-3 text-left">
                        <div class="border border-slate-200 bg-white/90 px-4 py-3 shadow-sm lg:px-5 lg:py-4">
                            <p class="text-[8px] font-black uppercase tracking-[0.18em] text-slate-400 lg:text-[10px]">
                                Certificate No.
                            </p>

                            <p class="mt-1 break-all text-[10px] font-black text-slate-900 lg:text-sm">
                                {{ $certificate->certificate_number }}
                            </p>
                        </div>

                        <div class="border border-slate-200 bg-white/90 px-4 py-3 shadow-sm lg:px-5 lg:py-4">
                            <p class="text-[8px] font-black uppercase tracking-[0.18em] text-slate-400 lg:text-[10px]">
                                Issued Date
                            </p>

                            <p class="mt-1 text-[10px] font-black text-slate-900 lg:text-sm">
                                {{ $certificate->issued_at?->format('d F Y') ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- SIGNATURE + VERIFY --}}
                    <div class="absolute left-[17%] right-[17%] top-[78.5%] grid grid-cols-2 items-center gap-8">
                        <div class="text-center">
                            @if ($signatureImageUrl)
                            <img
                                src="{{ $signatureImageUrl }}"
                                alt="{{ $signerName }}"
                                class="mx-auto h-10 w-full object-contain lg:h-14">
                            @else
                            <div class="mx-auto h-10 w-full lg:h-14"></div>
                            @endif

                            <div class="mx-auto mt-1 h-[2px] max-w-[260px] bg-slate-500"></div>

                            <p class="mt-3 text-xs font-black text-slate-900 lg:text-sm">
                                {{ $signerName }}
                            </p>

                            <p class="mt-1 text-[8px] font-bold uppercase tracking-[0.2em] text-slate-400 lg:text-[10px]">
                                {{ $signerTitle }}
                            </p>
                        </div>

                        <div class="text-center">
                            @if ($verificationUrl && $qrSvg)
                            <div class="inline-flex flex-col items-center justify-center border border-slate-200 bg-white/95 p-2 shadow-sm">
                                <div class="[&_svg]:h-16 [&_svg]:w-16 lg:[&_svg]:h-20 lg:[&_svg]:w-20">
                                    {!! $qrSvg !!}
                                </div>

                                <p class="mt-2 text-[8px] font-black uppercase tracking-[0.18em] text-slate-400 lg:text-[10px]">
                                    Scan to Verify
                                </p>

                                <p class="mt-1 text-[9px] font-bold text-slate-500 lg:text-[11px]">
                                    Verify Certificate
                                </p>
                            </div>
                            @else
                            <div class="inline-flex border border-dashed border-slate-300 bg-white/80 px-5 py-4">
                                <p class="text-xs font-bold text-slate-400">
                                    Verification link unavailable
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if ($verificationUrl)
                    <a
                        href="{{ $verificationUrl }}"
                        target="_blank"
                        aria-label="Verify Certificate"
                        class="absolute left-[58%] right-[17%] top-[78.5%] bottom-[6%] z-20">
                    </a>
                    @endif
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
                    Your certificate has been issued. You can print it or download the official PDF file.
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