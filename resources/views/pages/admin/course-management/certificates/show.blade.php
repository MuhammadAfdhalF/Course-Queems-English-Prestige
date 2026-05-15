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
? \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(120)->margin(1)->generate($verificationUrl)
: null;
@endphp

<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.certificates.index')"
        back-label="Back to Certificates">
        <x-slot:actions>
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

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
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
                            Certificate record for student course completion and final exam achievement.
                        </p>
                    </div>

                    <span class="inline-flex rounded-full px-4 py-2 text-xs font-extrabold uppercase tracking-[0.14em] {{ $statusClasses }}">
                        {{ $statusLabel }}
                    </span>
                </div>
            </x-admin.table-card>

            <x-admin.table-card class="overflow-hidden">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                    <h2 class="text-lg font-extrabold text-slate-900">
                        Certificate Preview
                    </h2>
                </div>

                <div class="bg-white p-6">
                    @if ($certificate->status === 'issued')
                    <div class="relative overflow-hidden rounded-[28px] border-[10px] border-[#071738] bg-[#fffdf6] px-8 py-10 text-center shadow-sm">
                        @if ($templateBackgroundUrl)
                        <img
                            src="{{ $templateBackgroundUrl }}"
                            alt="Certificate Template Background"
                            class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-white/35"></div>
                        @endif
                        @if (! $templateBackgroundUrl)
                        <div class="pointer-events-none absolute left-4 top-4 h-20 w-20 border-l-4 border-t-4 border-[#D4A017] opacity-80"></div>
                        <div class="pointer-events-none absolute right-4 top-4 h-20 w-20 border-r-4 border-t-4 border-[#D4A017] opacity-80"></div>
                        <div class="pointer-events-none absolute bottom-4 left-4 h-20 w-20 border-b-4 border-l-4 border-[#D4A017] opacity-80"></div>
                        <div class="pointer-events-none absolute bottom-4 right-4 h-20 w-20 border-b-4 border-r-4 border-[#D4A017] opacity-80"></div>
                        @endif
                        <div class="relative z-10">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white text-lg font-black text-[#071738] shadow-sm ring-1 ring-slate-200">
                                QEP
                            </div>

                            <p class="mt-5 text-xs font-extrabold uppercase tracking-[0.35em] text-[#D4A017]">
                                Queens English Prestige
                            </p>

                            <h2 class="mt-5 text-4xl font-black uppercase tracking-[0.18em] text-[#071738]">
                                Certificate
                            </h2>

                            <p class="mt-2 text-sm font-extrabold uppercase tracking-[0.28em] text-slate-500">
                                Of Completion
                            </p>

                            <div class="mx-auto mt-7 h-[2px] max-w-md bg-gradient-to-r from-transparent via-[#D4A017] to-transparent"></div>

                            <p class="mt-8 text-sm leading-7 text-slate-600">
                                This certificate is proudly presented to
                            </p>

                            <h3 class="mt-3 break-words text-4xl font-black leading-tight text-slate-950">
                                {{ $student?->name ?? 'Student Name' }}
                            </h3>

                            <div class="mx-auto mt-5 h-[2px] max-w-xl bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>

                            <p class="mt-6 text-sm leading-7 text-slate-600">
                                for successfully completing the course
                            </p>

                            <h4 class="mt-3 break-words text-3xl font-black leading-tight text-[#071738]">
                                {{ $courseLevel?->name ?? 'Course Name' }}
                            </h4>

                            <p class="mt-3 text-sm font-extrabold uppercase tracking-[0.12em] text-[#D4A017]">
                                {{ $courseProgram?->name ?? 'Queens English Prestige Program' }}
                            </p>

                            <div class="mx-auto mt-8 grid max-w-3xl gap-3 text-left md:grid-cols-3">
                                <div class="rounded-2xl border border-slate-200 bg-white/75 px-4 py-3">
                                    <p class="text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                        Certificate No.
                                    </p>
                                    <p class="mt-2 break-all text-xs font-black text-slate-900">
                                        {{ $certificate->certificate_number }}
                                    </p>
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-white/75 px-4 py-3">
                                    <p class="text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                        Issued Date
                                    </p>
                                    <p class="mt-2 text-xs font-black text-slate-900">
                                        {{ $certificate->issued_at?->format('d F Y') ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-white/75 px-4 py-3">
                                    <p class="text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                        Final Exam Score
                                    </p>
                                    <p class="mt-2 text-xs font-black text-slate-900">
                                        {{ $finalExamAttempt ? number_format((float) $finalExamAttempt->total_score, 2) . '%' : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="rounded-[24px] border border-dashed border-slate-300 bg-slate-50 px-6 py-16 text-center">
                        <h3 class="text-2xl font-extrabold text-slate-900">
                            Certificate Not Available
                        </h3>

                        <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-500">
                            This certificate preview is only available when the certificate status is issued.
                        </p>
                    </div>
                    @endif
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
                </div>
            </x-admin.table-card>

            <x-admin.table-card class="p-6">
                <h2 class="text-lg font-extrabold text-slate-900">
                    Exam & File
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
                            PDF File
                        </p>
                        <p class="mt-1 break-all text-sm font-bold text-slate-900">
                            {{ $certificate->certificate_file ?? 'Not generated yet' }}
                        </p>
                    </div>
                </div>
            </x-admin.table-card>
        </div>
    </div>
</section>
@endsection