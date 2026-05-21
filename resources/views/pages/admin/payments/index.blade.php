@extends('layouts.admin', [
'pageTitle' => 'Payments',
'pageSubtitle' => 'Payment Management',
])

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.dashboard')"
        back-label="Back to Dashboard" />

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="grid gap-5 lg:grid-cols-[1fr_auto] lg:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Payment Management
                </p>

                <h2 class="mt-2 text-2xl font-extrabold text-slate-900">
                    Recorded Payments
                </h2>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                    Review admin-recorded payments from manually confirmed student course orders.
                </p>
            </div>

            <div class="grid gap-3 sm:grid-cols-3">
                <div class="rounded-2xl bg-blue-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-blue-500">Total Paid</p>
                    <p class="mt-1 text-xl font-extrabold text-blue-700">
                        Rp {{ number_format((float) $totalPaid, 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-2xl bg-emerald-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-emerald-500">This Month</p>
                    <p class="mt-1 text-xl font-extrabold text-emerald-700">
                        Rp {{ number_format((float) $thisMonthPaid, 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-2xl bg-amber-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-amber-500">Paid Records</p>
                    <p class="mt-1 text-2xl font-extrabold text-amber-700">
                        {{ $paidPayments }}
                    </p>
                </div>
            </div>
        </div>
    </x-admin.table-card>

    <x-admin.table-card class="p-6">
        <form action="{{ route('admin.payments.index') }}" method="GET">
            <div class="grid gap-4 lg:grid-cols-[1fr_180px_200px_auto] lg:items-end">
                <div>
                    <label for="search" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Search
                    </label>

                    <input
                        id="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search order, student, email, or course..."
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label for="status" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="all" @selected($status==='all' )>All Status</option>
                        <option value="paid" @selected($status==='paid' )>Paid</option>
                        <option value="unpaid" @selected($status==='unpaid' )>Unpaid</option>
                        <option value="cancelled" @selected($status==='cancelled' )>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label for="method" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Method
                    </label>

                    <select
                        id="method"
                        name="method"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="all" @selected($method==='all' )>All Method</option>
                        <option value="manual_transfer" @selected($method==='manual_transfer' )>Manual Transfer</option>
                        <option value="cash" @selected($method==='cash' )>Cash</option>
                        <option value="other" @selected($method==='other' )>Other</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                        Filter
                    </button>

                    @if ($search || $status !== 'all' || $method !== 'all')
                    <a
                        href="{{ route('admin.payments.index') }}"
                        class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </x-admin.table-card>

    @include('partials.admin.payments.table')
</section>
@endsection