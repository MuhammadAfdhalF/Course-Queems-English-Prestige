@extends('layouts.admin', [
'pageTitle' => 'Certificate Detail',
'pageSubtitle' => $certificate->certificate_number,
])

@section('content')
@php
$statusClasses = match ($certificate->status) {
'issued' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100',
'revoked' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-100',
'locked' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-100',
default => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
};

$statusLabel = match ($certificate->status) {
'issued' => 'Issued',
'revoked' => 'Revoked',
'locked' => 'Locked',
default => ucfirst(str_replace('_', ' ', $certificate->status)),
};

$templateBackground = $certificate->certificateTemplate?->background_image;
$templateBackgroundUrl = $templateBackground
? asset('storage/' . $templateBackground)
: null;

$verificationUrl = $certificate->verification_token
? route('certificates.verify', $certificate->verification_token)
: null;

$qrSvg = $verificationUrl
? \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(96)->margin(1)->generate($verificationUrl)
: null;
@endphp

<style>
    .admin-certificate-preview-frame {
        aspect-ratio: 297 / 210;
    }
</style>

<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.certificates.index')"
        back-label="Back to Certificates">
        <x-slot:actions>
            @if ($verificationUrl)
            <a
                href="{{ $verificationUrl }}"
                target="_blank"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <span>Open Verification</span>
            </a>
            @endif

            @if ($certificate->status === 'issued')
            <a
                href="{{ route('admin.course-management.certificates.download', $certificate) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                <span>Download PDF</span>
            </a>

            <form
                action="{{ route('admin.course-management.certificates.revoke', $certificate) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to revoke this certificate?')">
                @csrf
                @method('PUT')

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700">
                    Revoke
                </button>
            </form>
            @elseif ($certificate->status === 'revoked')
            <form
                action="{{ route('admin.course-management.certificates.reissue', $certificate) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to re-issue this certificate?')">
                @csrf
                @method('PUT')

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                    Re-issue Certificate
                </button>
            </form>
            @endif
        </x-slot:actions>
    </x-admin.page-toolbar>

    <x-admin.flash-message />

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
        <div class="space-y-6">
            <x-admin.table-card class="p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Certificate Number
                        </p>

                        <h1 class="mt-2 break-all text-3xl font-extrabold text-slate-900">
                            {{ $certificate->certificate_number }}
                        </h1>

                        <p class="mt-3 text-sm leading-6 text-slate-500">
                            Certificate record for student course completion and public verification.
                        </p>
                    </div>

                    <span class="inline-flex rounded-full px-4 py-2 text-xs font-extrabold uppercase tracking-[0.14em] {{ $statusClasses }}">
                        {{ $statusLabel }}
                    </span>
                </div>
            </x-admin.table-card>

            <x-admin.table-card class="overflow-hidden">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">
                                Certificate Preview
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Admin preview for certificate verification and PDF layout checking.
                            </p>
                        </div>

                        @if ($certificate->certificateTemplate)
                        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                            {{ $certificate->certificateTemplate->name }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="bg-white p-4 lg:p-6">
                    <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-3 shadow-sm lg:p-4">
                        <div
                            class="admin-certificate-preview-frame relative mx-auto w-full overflow-hidden rounded-[22px] bg-[#fffdf6] text-center shadow-[0_18px_48px_rgba(15,23,42,0.12)]">

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

                            @if ($certificate->status !== 'issued')
                            <div class="absolute inset-0 z-20 flex items-center justify-center bg-white/45">
                                <span class="rotate-[-12deg] rounded-2xl border-4 border-current px-8 py-4 text-4xl font-black uppercase tracking-[0.2em] {{ $certificate->status === 'revoked' ? 'text-rose-600' : 'text-amber-600' }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            @endif

                            <div class="absolute inset-0 z-10">
                                {{-- HEADER --}}
                                <div class="absolute left-[11%] right-[11%] top-[7%] text-center">
                                    <div class="mx-auto flex h-10 w-10 items-center justify-center overflow-hidden rounded-full border border-slate-200 bg-white shadow-sm md:h-12 md:w-12 lg:h-14 lg:w-14">
                                        <img
                                            src="{{ asset('images/logo-queens-english.png') }}"
                                            alt="Queens English Prestige"
                                            class="h-8 w-auto object-contain md:h-9 lg:h-11"
                                            onerror="this.parentElement.innerHTML = '<span style=&quot;font-weight:900;color:#071738;font-size:15px;&quot;>QEP</span>';">
                                    </div>

                                    <p class="mt-2 text-[6px] font-black uppercase tracking-[0.34em] text-[#D4A017] md:text-[7px] lg:text-[8px]">
                                        Queens English Prestige
                                    </p>

                                    <h2 class="mt-3 text-2xl font-black uppercase leading-none tracking-[0.18em] text-[#071738] md:text-3xl lg:text-4xl">
                                        Certificate
                                    </h2>

                                    <p class="mt-2 text-[7px] font-black uppercase tracking-[0.32em] text-slate-500 md:text-[8px] lg:text-[9px]">
                                        Of Completion
                                    </p>

                                    <div class="mx-auto mt-3 h-[2px] max-w-[230px] bg-[#D4A017] md:max-w-[270px] lg:max-w-[310px]"></div>
                                </div>

                                {{-- RECIPIENT --}}
                                <div class="absolute left-[13%] right-[13%] top-[38%] text-center">
                                    <p class="text-[8px] font-medium text-slate-600 md:text-[9px] lg:text-[11px]">
                                        This certificate is proudly presented to
                                    </p>

                                    <h3 class="mt-2 break-words text-xl font-black leading-tight text-slate-950 md:text-2xl lg:text-3xl">
                                        {{ $student?->name ?? 'Student Name' }}
                                    </h3>

                                    <div class="mx-auto mt-3 h-px max-w-[420px] bg-slate-300"></div>

                                    <p class="mt-4 text-[8px] font-medium text-slate-600 md:text-[9px] lg:text-[11px]">
                                        for successfully completing the course
                                    </p>

                                    <h4 class="mt-2 break-words text-lg font-black leading-tight text-[#071738] md:text-xl lg:text-2xl">
                                        {{ $courseLevel?->name ?? 'Course Name' }}
                                    </h4>

                                    <p class="mt-2 text-[6px] font-black uppercase tracking-[0.16em] text-[#D4A017] md:text-[7px] lg:text-[8px]">
                                        {{ $courseProgram?->name ?? 'Queens English Prestige Program' }}
                                    </p>
                                </div>

                                {{-- METADATA --}}
                                <div class="absolute left-[25%] right-[25%] top-[68%] grid grid-cols-2 gap-3 text-left">
                                    <div class="border border-slate-200 bg-white/90 px-2.5 py-2 shadow-sm lg:px-3 lg:py-2.5">
                                        <p class="text-[5px] font-black uppercase tracking-[0.18em] text-slate-400 md:text-[6px] lg:text-[7px]">
                                            Certificate No.
                                        </p>

                                        <p class="mt-1 break-all text-[7px] font-black text-slate-900 md:text-[8px] lg:text-[9px]">
                                            {{ $certificate->certificate_number }}
                                        </p>
                                    </div>

                                    <div class="border border-slate-200 bg-white/90 px-2.5 py-2 shadow-sm lg:px-3 lg:py-2.5">
                                        <p class="text-[5px] font-black uppercase tracking-[0.18em] text-slate-400 md:text-[6px] lg:text-[7px]">
                                            Issued Date
                                        </p>

                                        <p class="mt-1 text-[7px] font-black text-slate-900 md:text-[8px] lg:text-[9px]">
                                            {{ $certificate->issued_at?->format('d F Y') ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- SIGNATURE + VERIFY --}}
                                <div class="absolute left-[18%] right-[18%] top-[81%] grid grid-cols-2 items-center gap-8">
                                    <div class="text-center">
                                        <div class="mx-auto h-[2px] max-w-[200px] bg-slate-500"></div>

                                        <p class="mt-2 text-[8px] font-black text-slate-900 md:text-[9px] lg:text-[10px]">
                                            Queens English Prestige
                                        </p>

                                        <p class="mt-1 text-[5px] font-bold uppercase tracking-[0.2em] text-slate-400 md:text-[6px] lg:text-[7px]">
                                            Authorized Signature
                                        </p>
                                    </div>

                                    <div class="text-center">
                                        @if ($verificationUrl && $qrSvg)
                                        <div class="inline-flex flex-col items-center justify-center border border-slate-200 bg-white/95 p-1.5 shadow-sm lg:p-2">
                                            <div class="[&_svg]:h-11 [&_svg]:w-11 md:[&_svg]:h-12 md:[&_svg]:w-12 lg:[&_svg]:h-14 lg:[&_svg]:w-14">
                                                {!! $qrSvg !!}
                                            </div>

                                            <p class="mt-1 text-[5px] font-black uppercase tracking-[0.18em] text-slate-400 md:text-[6px] lg:text-[7px]">
                                                Scan to Verify
                                            </p>

                                            <p class="mt-0.5 text-[6px] font-bold text-slate-500 md:text-[7px] lg:text-[8px]">
                                                Verify Certificate
                                            </p>
                                        </div>
                                        @else
                                        <div class="inline-flex border border-dashed border-slate-300 bg-white/80 px-4 py-3">
                                            <p class="text-[9px] font-bold text-slate-400">
                                                Verification unavailable
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
                                    class="absolute left-[58%] right-[18%] top-[81%] bottom-[7%] z-30">
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </x-admin.table-card>
        </div>

        <div class="space-y-6">
            <x-admin.table-card class="p-6">
                <h2 class="text-lg font-extrabold text-slate-900">
                    Student
                </h2>

                <div class="mt-5 space-y-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Name
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-900">
                            {{ $student?->name ?? 'Unknown Student' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Email
                        </p>
                        <p class="mt-1 break-all text-sm font-bold text-slate-900">
                            {{ $student?->email ?? '-' }}
                        </p>
                    </div>
                </div>
            </x-admin.table-card>

            <x-admin.table-card class="p-6">
                <h2 class="text-lg font-extrabold text-slate-900">
                    Course
                </h2>

                <div class="mt-5 space-y-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Program
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-900">
                            {{ $courseProgram?->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Level
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-900">
                            {{ $courseLevel?->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Template
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-900">
                            {{ $certificate->certificateTemplate?->name ?? '-' }}
                        </p>
                    </div>
                </div>
            </x-admin.table-card>

            <x-admin.table-card class="p-6">
                <h2 class="text-lg font-extrabold text-slate-900">
                    Certificate Info
                </h2>

                <div class="mt-5 space-y-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Status
                        </p>
                        <p class="mt-2">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-extrabold uppercase tracking-[0.12em] {{ $statusClasses }}">
                                {{ $statusLabel }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Issued Date
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-900">
                            {{ $certificate->issued_at?->format('d F Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            PDF File
                        </p>
                        <p class="mt-1 break-all text-sm font-bold text-slate-900">
                            {{ $certificate->certificate_file ?? 'Not generated yet' }}
                        </p>
                    </div>
                </div>
            </x-admin.table-card>

            <x-admin.table-card class="p-6">
                <h2 class="text-lg font-extrabold text-slate-900">
                    Exam & Verification
                </h2>

                <div class="mt-5 space-y-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Final Exam
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-900">
                            {{ $finalExamAttempt?->finalExam?->title ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Score
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-900">
                            {{ $finalExamAttempt ? number_format((float) $finalExamAttempt->total_score, 2) . '%' : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Verification URL
                        </p>

                        @if ($verificationUrl)
                        <a
                            href="{{ $verificationUrl }}"
                            target="_blank"
                            class="mt-1 block break-all text-sm font-bold text-[var(--color-brand-blue)] hover:underline">
                            {{ $verificationUrl }}
                        </a>
                        @else
                        <p class="mt-1 text-sm font-bold text-slate-900">
                            -
                        </p>
                        @endif
                    </div>
                </div>
            </x-admin.table-card>
        </div>
    </div>
</section>
@endsection