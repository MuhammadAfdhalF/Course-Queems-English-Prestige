@extends('layouts.admin', [
'pageTitle' => 'Order Detail',
'pageSubtitle' => $order->order_code,
])

@section('content')
@php
$orderStatusVariant = match ($order->status) {
'approved' => 'approved',
'rejected' => 'rejected',
'cancelled' => 'default',
default => 'pending',
};

$paymentStatusVariant = match ($payment?->payment_status) {
'paid' => 'completed',
'cancelled' => 'rejected',
default => 'pending',
};

$enrollmentStatusVariant = match ($enrollment?->status) {
'active' => 'approved',
'completed' => 'completed',
'cancelled' => 'rejected',
default => 'pending',
};

$paymentMethodLabel = match ($payment?->payment_method) {
'cash' => 'Cash',
'other' => 'Other',
'manual_transfer' => 'Manual Transfer',
default => '-',
};

$learningModeLabel = $courseLevel?->learning_mode
? str($courseLevel->learning_mode)->replace('_', ' ')->title()
: '-';

$accessTypeLabel = $courseLevel?->access_type
? str($courseLevel->access_type)->replace('_', ' ')->title()
: '-';

$rawWhatsapp = $profile?->whatsapp;
$normalizedWhatsapp = $rawWhatsapp
    ? preg_replace('/[^0-9]/', '', $rawWhatsapp)
    : null;

if ($normalizedWhatsapp) {
    if (str_starts_with($normalizedWhatsapp, '0')) {
        $normalizedWhatsapp = '62' . substr($normalizedWhatsapp, 1);
    } elseif (str_starts_with($normalizedWhatsapp, '8')) {
        $normalizedWhatsapp = '62' . $normalizedWhatsapp;
    }
}

$whatsappMessage = "Halo Kak *" . ($student?->name ?? 'Student') . "* 👋\n\n"
    . "Perkenalkan, kami dari *Queens English Prestige*.\n\n"
    . "Kami telah menerima pemesanan kursus Kakak dengan detail berikut:\n\n"
    . "*Kode Order:* " . ($order->order_code ?? '-') . "\n"
    . "*Kursus:* " . ($courseLevel?->name ?? '-') . "\n"
    . "*Biaya Kursus:* Rp " . number_format((float) $order->price, 0, ',', '.') . "\n"
    . "*Tanggal Pemesanan:* " . (($order->order_date ?? $order->created_at)?->format('d-m-Y H:i') ?? '-') . "\n\n"
    . "Saat ini pesanan Kakak masih menunggu konfirmasi pembayaran.\n\n"
    . "Silakan balas pesan ini agar kami dapat memberikan informasi pembayaran dan membantu proses aktivasi kursus Kakak.\n\n"
    . "Terima kasih 🙏\n"
    . "*Queens English Prestige*";

$whatsappUrl = $normalizedWhatsapp
    ? 'https://wa.me/' . $normalizedWhatsapp . ($order->status === 'pending' ? '?text=' . rawurlencode($whatsappMessage) : '')
    : null;
@endphp

<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.orders.index')"
        back-label="Back to Orders">
        <x-slot:actions>
            @if ($order->status === 'pending')
            <a
                href="{{ route('admin.orders.payment', $order) }}"
                class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                Record Payment & Approve
            </a>
            @endif

            @if ($payment)
            <a
                href="{{ route('admin.payments.show', $payment) }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                View Payment
            </a>
            @endif

            @if ($enrollment)
            <a
                href="{{ route('admin.course-access.show', $enrollment) }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                View Course Access
            </a>
            @endif
        </x-slot:actions>
    </x-admin.page-toolbar>

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Course Order
                </p>

                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <h1 class="text-3xl font-black text-slate-900">
                        {{ $order->order_code }}
                    </h1>

                    <x-admin.status-badge :variant="$orderStatusVariant">
                        {{ ucfirst($order->status) }}
                    </x-admin.status-badge>
                </div>

                <p class="mt-3 max-w-3xl text-sm font-semibold leading-6 text-slate-500">
                    Review student order, payment confirmation, course access, and timeline from one operational page.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 lg:min-w-[260px]">
                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                    Order Price
                </p>

                <p class="mt-2 text-2xl font-black text-[var(--color-brand-blue)]">
                    Rp {{ number_format((float) $order->price, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-xs font-semibold text-slate-500">
                    Ordered at {{ ($order->order_date ?? $order->created_at)?->format('d F Y H:i') ?? '-' }}
                </p>
            </div>
        </div>
    </x-admin.table-card>

    <div class="grid gap-6 xl:grid-cols-[1fr_380px]">
        <div class="space-y-6">
            {{-- ORDER SUMMARY --}}
            <x-admin.table-card class="p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Order Summary
                        </p>

                        <h2 class="mt-2 text-xl font-black text-slate-900">
                            Status & Order Information
                        </h2>
                    </div>

                    <x-admin.status-badge :variant="$orderStatusVariant">
                        {{ ucfirst($order->status) }}
                    </x-admin.status-badge>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Order Code</p>
                        <p class="mt-2 text-sm font-black text-slate-900">{{ $order->order_code }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Order Date</p>
                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ ($order->order_date ?? $order->created_at)?->format('d F Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Approved At</p>
                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ $order->approved_at?->format('d F Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Rejected At</p>
                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ $order->rejected_at?->format('d F Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 md:col-span-2">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Order Note</p>
                        <p class="mt-2 whitespace-pre-line text-sm font-semibold leading-6 text-slate-700">
                            {{ $order->note ?: '-' }}
                        </p>
                    </div>
                </div>
            </x-admin.table-card>

            {{-- PAYMENT SUMMARY --}}
            <x-admin.table-card class="p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Payment Summary
                        </p>

                        <h2 class="mt-2 text-xl font-black text-slate-900">
                            Admin Recorded Payment
                        </h2>
                    </div>

                    @if ($payment)
                    <x-admin.status-badge :variant="$paymentStatusVariant">
                        {{ ucfirst($payment->payment_status) }}
                    </x-admin.status-badge>
                    @endif
                </div>

                @if ($payment)
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Amount</p>
                        <p class="mt-2 text-xl font-black text-[var(--color-brand-blue)]">
                            Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Method</p>
                        <p class="mt-2 text-sm font-black text-slate-900">{{ $paymentMethodLabel }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Payment Date</p>
                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ $payment->payment_date?->format('d F Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Confirmed By</p>
                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ $payment->confirmedBy?->name ?? 'Admin' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 md:col-span-2">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Payment Note</p>
                        <p class="mt-2 whitespace-pre-line text-sm font-semibold leading-6 text-slate-700">
                            {{ $payment->note ?: '-' }}
                        </p>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-3">
                    <a
                        href="{{ route('admin.payments.show', $payment) }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white transition hover:opacity-90">
                        View Payment Detail
                    </a>

                    @if ($payment->proof_file)
                    <a
                        href="{{ asset('storage/' . $payment->proof_file) }}"
                        target="_blank"
                        class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        View Proof File
                    </a>
                    @endif
                </div>
                @else
                <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center">
                    <h3 class="text-base font-black text-slate-900">
                        No payment recorded yet
                    </h3>

                    <p class="mx-auto mt-2 max-w-xl text-sm font-semibold leading-6 text-slate-500">
                        Payment will appear here after admin records payment and approves this order.
                    </p>

                    @if ($order->status === 'pending')
                    <a
                        href="{{ route('admin.orders.payment', $order) }}"
                        class="mt-5 inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white transition hover:opacity-90">
                        Record Payment & Approve
                    </a>
                    @endif
                </div>
                @endif
            </x-admin.table-card>

            {{-- COURSE ACCESS SUMMARY --}}
            <x-admin.table-card class="p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Course Access
                        </p>

                        <h2 class="mt-2 text-xl font-black text-slate-900">
                            Enrollment Status
                        </h2>
                    </div>

                    @if ($enrollment)
                    <x-admin.status-badge :variant="$enrollmentStatusVariant">
                        {{ ucfirst($enrollment->status) }}
                    </x-admin.status-badge>
                    @endif
                </div>

                @if ($enrollment)
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Source</p>
                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ ucfirst($enrollment->enrollment_source) }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Progress</p>
                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ number_format((float) $enrollment->progress_percentage, 0) }}%
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Enrolled At</p>
                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ $enrollment->enrolled_at?->format('d F Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Expired At</p>
                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ $enrollment->expired_at?->format('d F Y H:i') ?? 'Unlimited' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 md:col-span-2">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Access Note</p>
                        <p class="mt-2 whitespace-pre-line text-sm font-semibold leading-6 text-slate-700">
                            {{ $enrollment->note ?: '-' }}
                        </p>
                    </div>
                </div>

                <div class="mt-5">
                    <a
                        href="{{ route('admin.course-access.show', $enrollment) }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white transition hover:opacity-90">
                        View Course Access Detail
                    </a>
                </div>
                @else
                <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center">
                    <h3 class="text-base font-black text-slate-900">
                        Course access has not been created
                    </h3>

                    <p class="mx-auto mt-2 max-w-xl text-sm font-semibold leading-6 text-slate-500">
                        Course access will be created automatically after payment is recorded and the order is approved.
                    </p>
                </div>
                @endif
            </x-admin.table-card>

            {{-- TIMELINE --}}
            <x-admin.table-card class="p-6">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Order Timeline
                </p>

                <h2 class="mt-2 text-xl font-black text-slate-900">
                    Status History
                </h2>

                <div class="mt-6 space-y-4">
                    @forelse ($timeline as $item)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <span class="mt-1 h-3 w-3 rounded-full bg-[var(--color-brand-blue)]"></span>

                            @if (! $loop->last)
                            <span class="mt-2 h-full min-h-10 w-px bg-slate-200"></span>
                            @endif
                        </div>

                        <div class="pb-4">
                            <h3 class="text-sm font-black text-slate-900">
                                {{ $item['title'] }}
                            </h3>

                            <p class="mt-1 text-sm font-semibold leading-6 text-slate-500">
                                {{ $item['description'] }}
                            </p>

                            <p class="mt-1 text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                                {{ $item['date']?->format('d F Y H:i') }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm font-semibold text-slate-500">
                        No timeline available.
                    </p>
                    @endforelse
                </div>
            </x-admin.table-card>
        </div>

        <aside class="space-y-6">
            {{-- STUDENT CARD --}}
            <x-admin.table-card class="p-6">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Student
                </p>

                <div class="mt-5 flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-50 text-lg font-black text-[var(--color-brand-blue)]">
                        {{ collect(explode(' ', $student?->name ?? 'ST'))->filter()->take(2)->map(fn($word) => strtoupper(substr($word, 0, 1)))->implode('') ?: 'ST' }}
                    </div>

                    <div class="min-w-0">
                        <h2 class="break-words text-lg font-black text-slate-900">
                            {{ $student?->name ?? '-' }}
                        </h2>

                        <p class="mt-1 break-all text-sm font-semibold text-slate-500">
                            {{ $student?->email ?? '-' }}
                        </p>

                        @if ($student)
                        <div class="mt-3">
                            <x-admin.status-badge :variant="$student->is_active ? 'completed' : 'rejected'">
                                {{ $student->is_active ? 'Active' : 'Inactive' }}
                            </x-admin.status-badge>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-emerald-500">WhatsApp</p>
                        <p class="mt-2 text-sm font-black text-emerald-700">
                            {{ $profile?->whatsapp ?? '-' }}
                        </p>

                        @if ($whatsappUrl)
                        <a
                            href="{{ $whatsappUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-3 inline-flex h-10 items-center justify-center rounded-xl bg-emerald-600 px-4 text-xs font-bold text-white transition hover:bg-emerald-700">
                            {{ $order->status === 'pending' ? 'Follow Up via WhatsApp' : 'Open WhatsApp' }}
                        </a>
                        @else
                        <span class="mt-3 inline-flex h-10 items-center justify-center rounded-xl bg-slate-200 px-4 text-xs font-bold text-slate-500 cursor-not-allowed">
                            WhatsApp Not Available
                        </span>
                        @endif
                    </div>

                    <div class="grid gap-3">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Profession</p>
                            <p class="mt-1 text-sm font-bold text-slate-700">{{ $profile?->profession ?: '-' }}</p>
                        </div>

                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Institution</p>
                            <p class="mt-1 text-sm font-bold text-slate-700">{{ $profile?->institution ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </x-admin.table-card>

            {{-- COURSE CARD --}}
            <x-admin.table-card class="p-6">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Course
                </p>

                <h2 class="mt-3 text-lg font-black text-slate-900">
                    {{ $courseLevel?->name ?? '-' }}
                </h2>

                <p class="mt-1 text-sm font-semibold text-slate-500">
                    {{ $courseProgram?->name ?? '-' }}
                </p>

                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Learning Mode</p>
                        <p class="mt-2 text-sm font-black text-slate-900">{{ $learningModeLabel }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Access Type</p>
                        <p class="mt-2 text-sm font-black text-slate-900">{{ $accessTypeLabel }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Access Duration</p>
                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ $courseLevel?->access_duration_days ? $courseLevel->access_duration_days . ' days' : 'Unlimited' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Course Price</p>
                        <p class="mt-2 text-xl font-black text-[var(--color-brand-blue)]">
                            Rp {{ number_format((float) ($courseLevel?->price ?? $order->price), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </x-admin.table-card>

            {{-- ACTION BOX --}}
            <x-admin.table-card class="p-6">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Action Center
                </p>

                <h2 class="mt-2 text-lg font-black text-slate-900">
                    Next Admin Action
                </h2>

                @if ($order->status === 'pending')
                <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                    This order is still pending. Confirm payment with student, then record payment or reject this order.
                </p>

                <div class="mt-5 space-y-3">
                    <a
                        href="{{ route('admin.orders.payment', $order) }}"
                        class="flex h-12 w-full items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white transition hover:opacity-90">
                        Record Payment & Approve
                    </a>

                    <form
                        action="{{ route('admin.orders.reject', $order) }}"
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to reject this order?')">
                        @csrf
                        @method('PUT')

                        <textarea
                            name="note"
                            rows="3"
                            placeholder="Reject note optional..."
                            class="mb-3 w-full resize-none rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-100">{{ old('note', $order->note) }}</textarea>

                        <button
                            type="submit"
                            class="flex h-12 w-full items-center justify-center rounded-xl border border-rose-200 bg-white px-5 text-sm font-bold text-rose-600 transition hover:bg-rose-50">
                            Reject Order
                        </button>
                    </form>
                </div>
                @elseif ($order->status === 'approved')
                <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                    This order has been approved. Payment and course access should already be available.
                </p>

                <div class="mt-5 space-y-3">
                    @if ($payment)
                    <a
                        href="{{ route('admin.payments.show', $payment) }}"
                        class="flex h-12 w-full items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white transition hover:opacity-90">
                        View Payment Detail
                    </a>
                    @endif

                    @if ($enrollment)
                    <a
                        href="{{ route('admin.course-access.show', $enrollment) }}"
                        class="flex h-12 w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        View Course Access
                    </a>
                    @endif
                </div>
                @elseif ($order->status === 'rejected')
                <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4">
                    <h3 class="text-sm font-black text-rose-700">
                        Order Rejected
                    </h3>

                    <p class="mt-2 text-sm font-semibold leading-6 text-rose-600">
                        This order has been rejected. Approve and payment actions are no longer available.
                    </p>
                </div>
                @else
                <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <h3 class="text-sm font-black text-slate-700">
                        No action available
                    </h3>

                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                        This order cannot be processed from this state.
                    </p>
                </div>
                @endif
            </x-admin.table-card>
        </aside>
    </div>
</section>
@endsection