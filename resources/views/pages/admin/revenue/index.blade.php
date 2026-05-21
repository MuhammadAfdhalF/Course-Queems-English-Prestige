@extends('layouts.admin', [
'pageTitle' => 'Revenue Report',
'pageSubtitle' => 'Payment Analytics',
])

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.dashboard')"
        back-label="Back to Dashboard" />

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl bg-blue-50 px-5 py-4">
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-blue-500">Total Revenue</p>
                <p class="mt-2 text-2xl font-black text-blue-700">
                    Rp {{ number_format((float) $totalRevenue, 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-2xl bg-emerald-50 px-5 py-4">
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-emerald-500">This Month</p>
                <p class="mt-2 text-2xl font-black text-emerald-700">
                    Rp {{ number_format((float) $thisMonthRevenue, 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-2xl bg-amber-50 px-5 py-4">
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-amber-500">Today</p>
                <p class="mt-2 text-2xl font-black text-amber-700">
                    Rp {{ number_format((float) $todayRevenue, 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-100 px-5 py-4">
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">Payments</p>
                <p class="mt-2 text-2xl font-black text-slate-800">
                    {{ $paymentCount }}
                </p>
            </div>
        </div>
    </x-admin.table-card>

    <x-admin.table-card class="p-6">
        <form action="{{ route('admin.revenue.index') }}" method="GET">
            <div class="grid gap-4 xl:grid-cols-[180px_180px_200px_220px_auto] xl:items-end">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Date From</label>
                    <input
                        type="date"
                        name="date_from"
                        value="{{ $dateFrom }}"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Date To</label>
                    <input
                        type="date"
                        name="date_to"
                        value="{{ $dateTo }}"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Method</label>
                    <select
                        name="method"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="all" @selected($method==='all' )>All Method</option>
                        <option value="manual_transfer" @selected($method==='manual_transfer' )>Manual Transfer</option>
                        <option value="cash" @selected($method==='cash' )>Cash</option>
                        <option value="other" @selected($method==='other' )>Other</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Program</label>
                    <select
                        name="program"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">All Programs</option>
                        @foreach ($programs as $programItem)
                        <option value="{{ $programItem->slug }}" @selected($program===$programItem->slug)>
                            {{ $programItem->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                        Filter
                    </button>

                    @if ($dateFrom || $dateTo || $method !== 'all' || $program)
                    <a
                        href="{{ route('admin.revenue.index') }}"
                        class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </x-admin.table-card>

    <div class="grid gap-6 xl:grid-cols-[1.4fr_0.6fr]">
        <x-admin.table-card>
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-xl font-black text-slate-900">
                    Monthly Revenue
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Paid payments in {{ now()->year }}.
                </p>
            </div>

            <div class="p-6">
                <div class="flex h-72 items-end gap-3 rounded-2xl bg-slate-50 p-6">
                    @foreach ($monthlyRevenue as $month)
                    <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                        <div
                            title="{{ $month['month'] }}: Rp {{ number_format((float) $month['total'], 0, ',', '.') }}"
                            class="w-full rounded-t-xl {{ (float) $month['total'] > 0 ? 'bg-[var(--color-brand-blue)]' : 'bg-blue-100' }}"
                            style="height: {{ $month['height'] }}%;">
                        </div>

                        <span class="text-[10px] font-bold uppercase text-slate-400">
                            {{ $month['month'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </x-admin.table-card>

        <x-admin.table-card>
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-xl font-black text-slate-900">
                    Revenue by Method
                </h2>
            </div>

            <div class="space-y-4 p-6">
                @forelse ($revenueByMethod as $methodKey => $amount)
                @php
                $methodLabel = match ($methodKey) {
                'cash' => 'Cash',
                'other' => 'Other',
                default => 'Manual Transfer',
                };
                @endphp

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-sm font-black text-slate-900">
                        {{ $methodLabel }}
                    </p>
                    <p class="mt-2 text-lg font-black text-[var(--color-brand-blue)]">
                        Rp {{ number_format((float) $amount, 0, ',', '.') }}
                    </p>
                </div>
                @empty
                <p class="text-sm font-semibold text-slate-500">
                    No revenue data yet.
                </p>
                @endforelse
            </div>
        </x-admin.table-card>
    </div>

    <x-admin.table-card>
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-xl font-black text-slate-900">
                Top Courses by Revenue
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        <th class="px-6 py-4">Course</th>
                        <th class="px-6 py-4">Revenue</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($revenueByCourse as $courseName => $amount)
                    <tr>
                        <td class="px-6 py-5 font-bold text-slate-900">
                            {{ $courseName }}
                        </td>
                        <td class="px-6 py-5 font-black text-[var(--color-brand-blue)]">
                            Rp {{ number_format((float) $amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-10 text-center text-sm text-slate-500">
                            No course revenue data yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.table-card>
</section>
@endsection