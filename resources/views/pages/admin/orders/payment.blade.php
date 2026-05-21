@extends('layouts.admin', [
'pageTitle' => 'Record Payment',
'pageSubtitle' => $order->order_code,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.orders.index')"
        back-label="Back to Orders" />

    <x-admin.flash-message />

    <x-admin.table-card class="overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                Record Payment & Approve
            </p>

            <h2 class="mt-2 text-2xl font-black text-slate-900">
                {{ $order->order_code }}
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Record admin-confirmed payment before approving this order and creating course access.
            </p>
        </div>

        <div class="grid gap-0 lg:grid-cols-[1fr_1fr]">
            <div class="border-b border-slate-200 p-6 lg:border-b-0 lg:border-r">
                <h3 class="text-lg font-black text-slate-900">
                    Order Summary
                </h3>

                <div class="mt-5 space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Student</p>
                        <p class="mt-2 text-sm font-black text-slate-900">{{ $student?->name ?? '-' }}</p>
                        <p class="mt-1 break-all text-xs font-semibold text-slate-500">{{ $student?->email ?? '-' }}</p>
                    </div>

                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-emerald-500">WhatsApp</p>

                        <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm font-black text-emerald-700">
                                {{ $student?->studentProfile?->whatsapp ?? '-' }}
                            </p>

                            @if ($student?->studentProfile?->whatsapp)
                            <a
                                href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->studentProfile->whatsapp) }}"
                                target="_blank"
                                class="inline-flex h-9 items-center justify-center rounded-xl bg-emerald-600 px-4 text-xs font-bold text-white transition hover:bg-emerald-700">
                                Open WA
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Course</p>
                        <p class="mt-2 text-sm font-black text-slate-900">{{ $courseLevel?->name ?? '-' }}</p>
                        <p class="mt-1 text-xs font-semibold text-slate-500">{{ $courseProgram?->name ?? '-' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Order Price</p>
                        <p class="mt-2 text-xl font-black text-[var(--color-brand-blue)]">
                            Rp {{ number_format((float) $order->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <form
                action="{{ route('admin.orders.payment.store', $order) }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-5 p-6">
                @csrf

                <div>
                    <label for="amount" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Payment Amount
                    </label>

                    <input
                        id="amount"
                        name="amount"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old('amount', $payment?->amount ?? $order->price) }}"
                        required
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">

                    @error('amount')
                    <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_method" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Payment Method
                    </label>

                    <select
                        id="payment_method"
                        name="payment_method"
                        required
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="manual_transfer" @selected(old('payment_method', $payment?->payment_method ?? 'manual_transfer') === 'manual_transfer')>
                            Manual Transfer
                        </option>
                        <option value="cash" @selected(old('payment_method', $payment?->payment_method) === 'cash')>
                            Cash
                        </option>
                        <option value="other" @selected(old('payment_method', $payment?->payment_method) === 'other')>
                            Other
                        </option>
                    </select>

                    @error('payment_method')
                    <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_date" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Payment Date
                    </label>

                    <input
                        id="payment_date"
                        name="payment_date"
                        type="datetime-local"
                        value="{{ old('payment_date', optional($payment?->payment_date ?? now())->format('Y-m-d\TH:i')) }}"
                        required
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">

                    @error('payment_date')
                    <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="proof_file" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Payment Proof <span class="text-slate-300">(optional)</span>
                    </label>

                    <input
                        id="proof_file"
                        name="proof_file"
                        type="file"
                        accept=".jpg,.jpeg,.png,.pdf,.webp"
                        class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-bold file:text-slate-600 hover:file:bg-slate-200">

                    <p class="mt-2 text-xs font-semibold text-slate-400">
                        Accepted: JPG, PNG, WEBP, PDF. Max 4MB.
                    </p>

                    @error('proof_file')
                    <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="note" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Admin Note <span class="text-slate-300">(optional)</span>
                    </label>

                    <textarea
                        id="note"
                        name="note"
                        rows="4"
                        placeholder="Write payment/order note..."
                        class="w-full resize-none rounded-xl border border-slate-200 px-4 py-4 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('note', $payment?->note ?? $order->note) }}</textarea>

                    @error('note')
                    <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                        Record Payment & Approve
                    </button>
                </div>
            </form>
        </div>
    </x-admin.table-card>
</section>
@endsection