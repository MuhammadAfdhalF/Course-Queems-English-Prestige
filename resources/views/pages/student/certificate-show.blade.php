@extends('layouts.learning')

@section('content')
@php
$templateBackground = $certificate->certificateTemplate?->background_image;
$templateBackgroundUrl = ($templateBackground && \Illuminate\Support\Facades\Storage::disk('public')->exists($templateBackground))
    ? asset('storage/' . $templateBackground)
    : asset('images/certificates/certificate-default-background.jpg');

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
    ? \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->margin(0)->generate($verificationUrl)
    : null;

$studentName = $student?->name ?? 'Student Name';
$studentNameLength = mb_strlen($studentName);

$studentProfile = $student?->studentProfile;
$birthPlace = trim($studentProfile?->birth_place ?? '');
$birthDateRaw = $studentProfile?->birth_date;

$birthDateMonthDay = $birthDateRaw ? $birthDateRaw->format('F j') : '';
$birthDateSuffix = $birthDateRaw ? $birthDateRaw->format('S') : '';
$birthDateYear = $birthDateRaw ? $birthDateRaw->format('Y') : '';
$hasBirthInfo = !empty($birthPlace) && !empty($birthDateRaw);

$courseName = $courseLevel?->name ?? 'English Language Program';
$issuedDateRaw = $certificate->issued_at ?? $certificate->created_at;

$completionDateDayOfWeekMonthDay = $issuedDateRaw ? $issuedDateRaw->format('l, F j') : date('l, F j');
$completionDateSuffix = $issuedDateRaw ? $issuedDateRaw->format('S') : date('S');
$completionDateYear = $issuedDateRaw ? $issuedDateRaw->format('Y') : date('Y');

$signingDateMonthDay = $issuedDateRaw ? $issuedDateRaw->format('F j') : date('F j');
$signingDateSuffix = $issuedDateRaw ? $issuedDateRaw->format('S') : date('S');
$signingDateYear = $issuedDateRaw ? $issuedDateRaw->format('Y') : date('Y');

$hasSectionScores = is_array($certificate->section_scores) && !empty($certificate->section_scores);
$sectionScores = $hasSectionScores ? $certificate->section_scores : [];
$finalScoreFormatted = $certificate->final_score !== null ? number_format((float) $certificate->final_score, 2, '.', '') : null;
$sectionCount = count($sectionScores);
@endphp

<style>
    .certificate-preview-frame {
        aspect-ratio: 297 / 210;
    }

    .date-ordinal sup, sup {
        font-size: 60%;
        vertical-align: super;
        line-height: 0;
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
                class="certificate-preview-frame relative mx-auto w-full overflow-hidden rounded-[26px] bg-white text-center shadow-[0_22px_70px_rgba(15,23,42,0.14)]">

                <img
                    src="{{ $templateBackgroundUrl }}"
                    alt="Certificate Template Background"
                    class="absolute inset-0 h-full w-full object-fill">

                <div class="absolute inset-0 z-10">
                    {{-- MAIN CONTENT AREA (Preserved at top-[24%]) --}}
                    <div class="absolute left-[18.5%] right-[8.5%] top-[24%] text-center">
                        <h1 class="text-[1.7rem] font-bold uppercase leading-tight tracking-[0.08em] text-[#0c1e38] sm:text-[2.2rem] lg:text-[2.6rem]">
                            Certificate Of Achievement
                        </h1>

                        <p class="mt-1 text-xs font-bold text-[#0f172a] sm:text-sm lg:text-base">
                            No: {{ $certificate->certificate_number }}
                        </p>

                        <p class="mt-2.5 text-xs font-medium uppercase tracking-[0.18em] text-[#1e293b] sm:text-sm lg:text-base">
                            This certificate is proudly presented to
                        </p>

                        {{-- Dynamic Student Name with Length Scaling --}}
                        @if ($studentNameLength <= 25)
                        <h2 class="mt-2 break-words text-2xl font-bold leading-tight text-[#c68b29] sm:text-3xl lg:text-5xl">
                            {{ $studentName }}
                        </h2>
                        @elseif ($studentNameLength <= 40)
                        <h2 class="mt-2 break-words text-xl font-bold leading-tight text-[#c68b29] sm:text-2xl lg:text-4xl">
                            {{ $studentName }}
                        </h2>
                        @else
                        <h2 class="mt-2 break-words text-lg font-bold leading-tight text-[#c68b29] sm:text-xl lg:text-3xl">
                            {{ $studentName }}
                        </h2>
                        @endif

                        {{-- Description --}}
                        <p class="mt-2.5 text-[0.65rem] font-normal leading-relaxed text-[#1e293b] sm:text-xs lg:text-sm">
                            @if ($hasBirthInfo)
                            born in {{ $birthPlace }}, on <span class="date-ordinal">{{ $birthDateMonthDay }}<sup>{{ $birthDateSuffix }}</sup>, {{ $birthDateYear }}</span> for the completion of
                            @else
                            for the completion of
                            @endif
                            <br>
                            <strong class="font-bold text-[#0f172a]">{{ $courseName }}</strong> on <span class="date-ordinal">{{ $completionDateDayOfWeekMonthDay }}<sup>{{ $completionDateSuffix }}</sup>, {{ $completionDateYear }}</span>.
                        </p>

                        {{-- Score Table on Page 1 if 1 to 5 sections --}}
                        @if ($hasSectionScores && $sectionCount <= 5)
                        <div class="mt-2 text-center">
                            <p class="mb-1 text-[0.6rem] font-bold text-[#0f172a] sm:text-xs">
                                TOEFL Prediction Score:
                            </p>
                            <table class="mx-auto w-[54%] border-collapse border-[1.5px] border-black bg-white text-[0.6rem] sm:text-xs">
                                <tbody>
                                    @foreach ($sectionScores as $idx => $sec)
                                    <tr>
                                        <td class="w-[72%] border border-black px-2 py-0.5 text-left">{{ $sec['title'] ?? 'Section ' . ($idx + 1) }}</td>
                                        <td class="w-[28%] border border-black px-2 py-0.5 text-center font-bold">{{ isset($sec['score']) ? number_format((float)$sec['score'], 0) : '-' }}</td>
                                    </tr>
                                    @endforeach
                                    @if ($finalScoreFormatted !== null)
                                    <tr class="font-bold">
                                        <td class="border border-black px-2 py-0.5 text-left">Total Score:</td>
                                        <td class="border border-black px-2 py-0.5 text-center">{{ $finalScoreFormatted }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <p class="mt-1 text-[0.55rem] italic text-black sm:text-[0.65rem]">
                                This prediction score is valid for 6 months from the date of issuance
                            </p>
                        </div>
                        @endif
                    </div>

                    {{-- BOTTOM-LEFT DYNAMIC VERIFICATION QR CODE (Revision 3: left-[6%], bottom-[12%]) --}}
                    @if ($verificationUrl && $qrSvg)
                    <div class="absolute bottom-[12%] left-[6%] w-[13%] text-center">
                        <a href="{{ $verificationUrl }}" target="_blank" class="inline-block text-center transition hover:opacity-90">
                            <div class="[&_svg]:mx-auto [&_svg]:h-10 [&_svg]:w-10 sm:[&_svg]:h-14 sm:[&_svg]:w-14 lg:[&_svg]:h-18 lg:[&_svg]:w-18">
                                {!! $qrSvg !!}
                            </div>
                            <p class="mt-1 text-[0.55rem] font-bold uppercase tracking-wider text-[#0f172a] sm:text-[0.65rem]">
                                SCAN TO VERIFY
                            </p>
                            <p class="text-[0.5rem] font-medium text-slate-500 sm:text-[0.6rem] break-all">
                                {{ $certificate->certificate_number }}
                            </p>
                        </a>
                    </div>
                    @endif

                    {{-- BOTTOM SIGNATURE BLOCK (Revision 3 Tweak: bottom-[12%], centered relative to content area at left-[55%]) --}}
                    <div class="absolute bottom-[12%] left-[55%] w-[26%] -translate-x-1/2 text-center">
                        <p class="text-[0.65rem] font-bold text-black sm:text-xs">
                            Pekanbaru, <span class="date-ordinal">{{ $signingDateMonthDay }}<sup>{{ $signingDateSuffix }}</sup>, {{ $signingDateYear }}</span>
                        </p>

                        <div class="my-0.5 flex h-7 items-center justify-center sm:h-10 lg:h-12">
                            @if ($signatureImageUrl)
                            <img src="{{ $signatureImageUrl }}" alt="{{ $signerName }}" class="max-h-full object-contain">
                            @endif
                        </div>

                        <p class="text-[0.7rem] font-bold uppercase tracking-wide text-[#c68b29] underline sm:text-xs lg:text-sm">
                            {{ $signerName }}
                        </p>

                        <p class="text-[0.6rem] font-medium text-[#1e293b] sm:text-[0.7rem] lg:text-xs">
                            {{ $signerTitle }}
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