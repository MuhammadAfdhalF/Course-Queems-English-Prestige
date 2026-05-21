@extends('layouts.admin', [
'pageTitle' => 'Payment Detail',
'pageSubtitle' => $order?->order_code ?? 'Payment',
])

@section('content')
@php
$methodLabel = match ($payment->payment_method) {
'cash' => 'Cash',
'other' => 'Other',
default => 'Manual Transfer',
};

$statusVariant = match ($payment->payment_status) {
'paid' => 'completed',
'cancelled' => 'rejected',
default => 'pending',
};
@endphp

<section class="mx-auto max-w-6xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.payments.index')"
        back-label="Back to Payments" />

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Payment Detail
                </p>

                <h1 class="mt-2 text-3xl font-black text-slate-900">
                    {{ $order?->order_code ?? 'Payment #' . $payment->id }}
                </h1>

                <p class="mt-2 text-sm font-semibold text-slate-500">
                    Confirmed by {{ $confirmedBy?->name ?? 'Admin' }}
                </p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Amount</p>
                        <p class="mt-2 text-2xl font-black text-[var(--color-brand-blue)]">
                            Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Status</p>
                        <div class="mt-2">
                            <x-admin.status-badge :variant="$statusVariant">
                                {{ ucfirst($payment->payment_status) }}
                            </x-admin.status-badge>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Method</p>
                        <p class="mt-2 text-sm font-black text-slate-900">{{ $methodLabel }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Payment Date</p>
                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ $payment->payment_date?->format('d F Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 md:col-span-2">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Note</p>
                        <p class="mt-2 whitespace-pre-line text-sm font-semibold leading-6 text-slate-700">
                            {{ $payment->note ?: '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Student</p>
                    <p class="mt-2 text-sm font-black text-slate-900">{{ $student?->name ?? '-' }}</p>
                    <p class="mt-1 break-all text-xs font-semibold text-slate-500">{{ $student?->email ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Course</p>
                    <p class="mt-2 text-sm font-black text-slate-900">{{ $courseLevel?->name ?? '-' }}</p>
                    <p class="mt-1 text-xs font-semibold text-slate-500">{{ $courseProgram?->name ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Proof File</p>

                    @if ($payment->proof_file)
                    <a
                        href="{{ asset('storage/' . $payment->proof_file) }}"
                        target="_blank"
                        class="mt-3 inline-flex h-10 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-xs font-bold text-white transition hover:opacity-90">
                        View Proof
                    </a>
                    @else
                    <p class="mt-2 text-sm font-semibold text-slate-500">
                        No proof uploaded.
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </x-admin.table-card>
</section>
@endsection