@extends('layouts.public', [
'title' => 'Certificate Verification - Queens English Prestige',
])

@section('content')
@php
$status = $certificate?->status;

$statusConfig = match ($status) {
'issued' => [
'label' => 'Certificate Valid',
'description' => 'This certificate is valid and has been officially issued by Queens English Prestige.',
'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
'iconBg' => 'bg-emerald-100 text-emerald-700',
'panel' => 'border-emerald-200 bg-emerald-50',
],
'revoked' => [
'label' => 'Certificate Revoked',
'description' => 'This certificate was found, but it has been revoked and is no longer valid.',
'badge' => 'bg-rose-50 text-rose-700 ring-rose-100',
'iconBg' => 'bg-rose-100 text-rose-700',
'panel' => 'border-rose-200 bg-rose-50',
],
'locked' => [
'label' => 'Certificate Not Issued Yet',
'description' => 'This certificate record exists, but it has not been issued yet.',
'badge' => 'bg-amber-50 text-amber-700 ring-amber-100',
'iconBg' => 'bg-amber-100 text-amber-700',
'panel' => 'border-amber-200 bg-amber-50',
],
default => [
'label' => 'Certificate Not Found',
'description' => 'We could not find a certificate matching this verification link.',
'badge' => 'bg-slate-100 text-slate-600 ring-slate-200',
'iconBg' => 'bg-slate-100 text-slate-500',
'panel' => 'border-slate-200 bg-slate-50',
],
};
@endphp

<section class="bg-slate-50">
    <div class="mx-auto max-w-5xl px-4 py-16 lg:px-8 lg:py-24">
        <div class="text-center">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full {{ $statusConfig['iconBg'] }}">
                @if ($status === 'issued')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                </svg>
                @elseif ($status === 'revoked')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                </svg>
                @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8h.01M11 12h1v4h1" />
                </svg>
                @endif
            </div>

            <p class="mt-6 text-xs font-extrabold uppercase tracking-[0.24em] text-[var(--color-brand-gold)]">
                Queens English Prestige
            </p>

            <h1 class="mt-4 text-4xl font-black tracking-tight text-slate-900 lg:text-6xl">
                Certificate Verification
            </h1>

            <p class="mx-auto mt-5 max-w-2xl text-base leading-8 text-slate-500 lg:text-lg">
                Verify the authenticity and current status of a Queens English Prestige certificate.
            </p>
        </div>

        <div class="mt-12 overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
            <div class="border-b {{ $statusConfig['panel'] }} px-6 py-8 text-center lg:px-10">
                <span class="inline-flex rounded-full px-4 py-2 text-xs font-extrabold uppercase tracking-[0.16em] ring-1 {{ $statusConfig['badge'] }}">
                    {{ $statusConfig['label'] }}
                </span>

                <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-slate-600">
                    {{ $statusConfig['description'] }}
                </p>
            </div>

            @if ($certificate)
            <div class="grid gap-0 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="p-6 lg:p-10">
                    <h2 class="text-2xl font-black text-slate-900">
                        Certificate Details
                    </h2>

                    <div class="mt-8 grid gap-5 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                                Student Name
                            </p>
                            <p class="mt-2 text-base font-black text-slate-900">
                                {{ $student?->name ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                                Certificate Number
                            </p>
                            <p class="mt-2 break-all text-base font-black text-slate-900">
                                {{ $certificate->certificate_number }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                                Course Program
                            </p>
                            <p class="mt-2 text-base font-black text-slate-900">
                                {{ $courseProgram?->name ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                                Course Level
                            </p>
                            <p class="mt-2 text-base font-black text-slate-900">
                                {{ $courseLevel?->name ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 sm:col-span-2">
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                                Issued Date
                            </p>
                            <p class="mt-2 text-base font-black text-slate-900">
                                {{ $certificate->issued_at?->format('d F Y') ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200 bg-slate-50 p-6 lg:border-l lg:border-t-0 lg:p-10">
                    <h3 class="text-xl font-black text-slate-900">
                        Verification Summary
                    </h3>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-center justify-between gap-4 rounded-2xl bg-white px-5 py-4 ring-1 ring-slate-200">
                            <span class="text-sm font-bold text-slate-500">Status</span>
                            <span class="text-sm font-black text-slate-900">{{ ucfirst($certificate->status) }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-4 rounded-2xl bg-white px-5 py-4 ring-1 ring-slate-200">
                            <span class="text-sm font-bold text-slate-500">Verified At</span>
                            <span class="text-sm font-black text-slate-900">{{ now()->format('d M Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-4 rounded-2xl bg-white px-5 py-4 ring-1 ring-slate-200">
                            <span class="text-sm font-bold text-slate-500">Issuer</span>
                            <span class="text-right text-sm font-black text-slate-900">Queens English Prestige</span>
                        </div>
                    </div>

                    @if ($status === 'issued')
                    <p class="mt-6 rounded-2xl bg-emerald-50 px-5 py-4 text-sm font-semibold leading-6 text-emerald-700">
                        This certificate is currently valid.
                    </p>
                    @elseif ($status === 'revoked')
                    <p class="mt-6 rounded-2xl bg-rose-50 px-5 py-4 text-sm font-semibold leading-6 text-rose-700">
                        This certificate has been revoked by the issuer.
                    </p>
                    @else
                    <p class="mt-6 rounded-2xl bg-amber-50 px-5 py-4 text-sm font-semibold leading-6 text-amber-700">
                        This certificate has not been issued yet.
                    </p>
                    @endif
                </div>
            </div>
            @else
            <div class="p-8 text-center lg:p-12">
                <h2 class="text-2xl font-black text-slate-900">
                    No certificate record found
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-500">
                    Please check the verification link or contact Queens English Prestige for assistance.
                </p>
            </div>
            @endif
        </div>

        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
            <a
                href="{{ route('home') }}"
                class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                Back to Home
            </a>

            <a
                href="{{ route('courses') }}"
                class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-300 bg-white px-6 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                Browse Courses
            </a>
        </div>
    </div>
</section>
@endsection