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
                            class="admin-certificate-preview-frame relative mx-auto w-full overflow-hidden rounded-[22px] bg-white text-center shadow-[0_18px_48px_rgba(15,23,42,0.12)]">

                            <img
                                src="{{ $templateBackgroundUrl ?: asset('images/certificates/certificate-default-background.jpg') }}"
                                alt="Certificate Template Background"
                                class="absolute inset-0 h-full w-full object-fill">

                            @if ($certificate->status !== 'issued')
                            <div class="absolute inset-0 z-20 flex items-center justify-center bg-white/45">
                                <span class="rotate-[-12deg] rounded-2xl border-4 border-current px-8 py-4 text-4xl font-black uppercase tracking-[0.2em] {{ $certificate->status === 'revoked' ? 'text-rose-600' : 'text-amber-600' }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            @endif

                            <div class="absolute inset-0 z-10">
                                @php
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

                                {{-- MAIN CONTENT AREA (Shifted down to top-[24%]) --}}
                                <div class="absolute left-[18.5%] right-[8.5%] top-[24%] text-center">
                                    <h2 class="text-xl font-bold uppercase leading-tight tracking-[0.08em] text-[#0c1e38] md:text-2xl lg:text-3xl">
                                        Certificate Of Achievement
                                    </h2>

                                    <p class="mt-1 text-[10px] font-bold text-[#0f172a] md:text-xs lg:text-sm">
                                        No: {{ $certificate->certificate_number }}
                                    </p>

                                    <p class="mt-2 text-[9px] font-medium uppercase tracking-[0.18em] text-[#1e293b] md:text-[10px] lg:text-xs">
                                        This certificate is proudly presented to
                                    </p>

                                    {{-- Dynamic Student Name with Length Scaling --}}
                                    @if ($studentNameLength <= 25)
                                    <h3 class="mt-1.5 break-words text-lg font-bold leading-tight text-[#c68b29] md:text-xl lg:text-3xl">
                                        {{ $studentName }}
                                    </h3>
                                    @elseif ($studentNameLength <= 40)
                                    <h3 class="mt-1.5 break-words text-base font-bold leading-tight text-[#c68b29] md:text-lg lg:text-2xl">
                                        {{ $studentName }}
                                    </h3>
                                    @else
                                    <h3 class="mt-1.5 break-words text-sm font-bold leading-tight text-[#c68b29] md:text-base lg:text-xl">
                                        {{ $studentName }}
                                    </h3>
                                    @endif

                                    {{-- Description --}}
                                    <p class="mt-2 text-[8px] font-normal leading-relaxed text-[#1e293b] md:text-[9px] lg:text-[11px]">
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
                                    <div class="mt-1.5 text-center">
                                        <p class="mb-0.5 text-[8px] font-bold text-[#0f172a] md:text-[9px]">
                                            TOEFL Prediction Score:
                                        </p>
                                        <table class="mx-auto w-[54%] border-collapse border border-black bg-white text-[7px] md:text-[8px]">
                                            <tbody>
                                                @foreach ($sectionScores as $idx => $sec)
                                                <tr>
                                                    <td class="w-[72%] border border-black px-1.5 py-0.5 text-left">{{ $sec['title'] ?? 'Section ' . ($idx + 1) }}</td>
                                                    <td class="w-[28%] border border-black px-1.5 py-0.5 text-center font-bold">{{ isset($sec['score']) ? number_format((float)$sec['score'], 0) : '-' }}</td>
                                                </tr>
                                                @endforeach
                                                @if ($finalScoreFormatted !== null)
                                                <tr class="font-bold">
                                                    <td class="border border-black px-1.5 py-0.5 text-left">Total Score:</td>
                                                    <td class="border border-black px-1.5 py-0.5 text-center">{{ $finalScoreFormatted }}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        <p class="mt-0.5 text-[6.5px] italic text-black md:text-[7.5px]">
                                            This prediction score is valid for 6 months from the date of issuance
                                        </p>
                                    </div>
                                    @endif
                                </div>

                                {{-- BOTTOM-LEFT DYNAMIC VERIFICATION QR CODE (Revision 3: left-[6%], bottom-[12%]) --}}
                                @if ($verificationUrl && $qrSvg)
                                <div class="absolute bottom-[12%] left-[6%] w-[13%] text-center">
                                    <a href="{{ $verificationUrl }}" target="_blank" class="inline-block text-center transition hover:opacity-90">
                                        <div class="[&_svg]:mx-auto [&_svg]:h-8 [&_svg]:w-8 md:[&_svg]:h-10 md:[&_svg]:w-10 lg:[&_svg]:h-14 lg:[&_svg]:w-14">
                                            {!! $qrSvg !!}
                                        </div>
                                        <p class="mt-0.5 text-[5px] font-bold uppercase tracking-wider text-[#0f172a] md:text-[6px] lg:text-[7px]">
                                            SCAN TO VERIFY
                                        </p>
                                        <p class="text-[4.5px] font-medium text-slate-500 md:text-[5.5px] lg:text-[6.5px] break-all">
                                            {{ $certificate->certificate_number }}
                                        </p>
                                    </a>
                                </div>
                                @endif

                                {{-- BOTTOM SIGNATURE BLOCK (Revision 3 Tweak: bottom-[12%], centered relative to content area at left-[55%]) --}}
                                <div class="absolute bottom-[12%] left-[55%] w-[26%] -translate-x-1/2 text-center">
                                    <p class="text-[8px] font-bold text-black md:text-[9px] lg:text-[11px]">
                                        Pekanbaru, <span class="date-ordinal">{{ $signingDateMonthDay }}<sup>{{ $signingDateSuffix }}</sup>, {{ $signingDateYear }}</span>
                                    </p>

                                    <div class="my-0.5 flex h-6 items-center justify-center md:h-8 lg:h-10">
                                        @if ($signatureImageUrl)
                                        <img src="{{ $signatureImageUrl }}" alt="{{ $signerName }}" class="max-h-full object-contain">
                                        @endif
                                    </div>

                                    <p class="text-[8px] font-bold uppercase tracking-wide text-[#c68b29] underline md:text-[9px] lg:text-[11px]">
                                        {{ $signerName }}
                                    </p>

                                    <p class="text-[7px] font-medium text-[#1e293b] md:text-[8px] lg:text-[9px]">
                                        {{ $signerTitle }}
                                    </p>
                                </div>
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
                    Signature Setting
                </h2>

                <div class="mt-5 space-y-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Signer Name
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-900">
                            {{ $signerName }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Signer Title
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-900">
                            {{ $signerTitle }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Signature Image
                        </p>

                        @if ($signatureImageUrl)
                        <div class="mt-2 rounded-2xl border border-slate-200 bg-slate-50 p-3">
                            <img
                                src="{{ $signatureImageUrl }}"
                                alt="{{ $signerName }}"
                                class="h-16 w-full object-contain">
                        </div>
                        @else
                        <p class="mt-1 text-sm font-bold text-slate-900">
                            Not uploaded
                        </p>
                        @endif
                    </div>

                    <a
                        href="{{ route('admin.course-management.certificate-settings.edit') }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                        Edit Certificate Settings
                    </a>
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