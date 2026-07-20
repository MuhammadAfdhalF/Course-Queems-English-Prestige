@extends('layouts.admin', [
'pageTitle' => 'Revenue Report',
'pageSubtitle' => 'Payment Analytics',
])

@section('content')
@php
    $chartWidth = 760;
    $chartHeight = 360;

    $paddingLeft = 34;
    $paddingRight = 34;
    $paddingTop = 34;
    $paddingBottom = 54;

    $plotWidth = $chartWidth - $paddingLeft - $paddingRight;
    $plotHeight = $chartHeight - $paddingTop - $paddingBottom;

    $maxRevenue = max(1, collect($monthlyRevenue)->max('total'));
    $monthCount = max(1, count($monthlyRevenue) - 1);

    $points = collect($monthlyRevenue)
        ->values()
        ->map(function (array $month, int $index) use (
            $paddingLeft,
            $paddingTop,
            $plotWidth,
            $plotHeight,
            $monthCount,
            $maxRevenue
        ) {
            $x = $paddingLeft + (($plotWidth / $monthCount) * $index);
            $y = $paddingTop + ($plotHeight - (((float) $month['total'] / $maxRevenue) * $plotHeight));

            return [
                'x' => round($x, 2),
                'y' => round($y, 2),
                'month' => $month['month'],
                'total' => (float) $month['total'],
            ];
        });

    $pointString = $points
        ->map(fn ($point) => $point['x'] . ',' . $point['y'])
        ->implode(' ');

    $baselineY = $paddingTop + $plotHeight;

    $areaString = $paddingLeft . ',' . $baselineY
        . ' ' . $pointString
        . ' ' . ($paddingLeft + $plotWidth) . ',' . $baselineY;

    $activePoints = $points->filter(fn ($point) => $point['total'] > 0);
@endphp

<section class="mx-auto max-w-7xl space-y-7">
    <x-admin.page-toolbar
        :back-url="route('admin.dashboard')"
        back-label="Back to Dashboard" />

    <x-admin.flash-message />

    <div class="overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-sm">
        <div class="grid gap-0 xl:grid-cols-[1.35fr_0.65fr]">
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-[var(--color-brand-blue)] to-slate-800 p-7 text-white lg:p-8">
                <div class="pointer-events-none absolute -right-20 -top-20 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-24 left-20 h-56 w-56 rounded-full bg-[#AD6B10]/25 blur-3xl"></div>

                <div class="relative">
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-white/50">
                        Revenue Report
                    </p>

                    <h1 class="mt-4 text-4xl font-black leading-tight lg:text-5xl">
                        Payment Analytics
                    </h1>

                    <p class="mt-4 max-w-2xl text-sm font-semibold leading-7 text-white/70">
                        Track paid revenue, payment method performance, and top course contribution from approved student orders.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">
                        <a
                            href="{{ route('admin.payments.index') }}"
                            class="inline-flex h-11 items-center justify-center rounded-xl bg-white px-5 text-sm font-black text-[var(--color-brand-blue)] transition hover:bg-[#f8efcf]">
                            View Payments
                        </a>

                        <a
                            href="{{ route('admin.orders.index') }}"
                            class="inline-flex h-11 items-center justify-center rounded-xl border border-white/20 bg-white/10 px-5 text-sm font-black text-white backdrop-blur-sm transition hover:bg-white/15">
                            Manage Orders
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid gap-3 bg-white p-6 sm:grid-cols-2 xl:grid-cols-1">
                <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.16em] text-blue-500">
                        Total Revenue
                    </p>

                    <p class="mt-2 text-3xl font-black text-blue-700">
                        Rp {{ number_format((float) $totalRevenue, 0, ',', '.') }}
                    </p>

                    <p class="mt-1 text-xs font-bold text-blue-600/80">
                        All paid payments
                    </p>
                </div>

                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.16em] text-emerald-500">
                        This Month
                    </p>

                    <p class="mt-2 text-3xl font-black text-emerald-700">
                        Rp {{ number_format((float) $thisMonthRevenue, 0, ',', '.') }}
                    </p>

                    <p class="mt-1 text-xs font-bold text-emerald-600/80">
                        Current month revenue
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[24px] border border-blue-100 bg-blue-50 p-5">
            <p class="text-[11px] font-black uppercase tracking-[0.16em] text-blue-500">
                Total Revenue
            </p>

            <p class="mt-3 text-2xl font-black text-blue-700">
                Rp {{ number_format((float) $totalRevenue, 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-[24px] border border-emerald-100 bg-emerald-50 p-5">
            <p class="text-[11px] font-black uppercase tracking-[0.16em] text-emerald-500">
                This Month
            </p>

            <p class="mt-3 text-2xl font-black text-emerald-700">
                Rp {{ number_format((float) $thisMonthRevenue, 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-[24px] border border-amber-100 bg-amber-50 p-5">
            <p class="text-[11px] font-black uppercase tracking-[0.16em] text-amber-500">
                Today
            </p>

            <p class="mt-3 text-2xl font-black text-amber-700">
                Rp {{ number_format((float) $todayRevenue, 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-[24px] border border-slate-200 bg-white p-5">
            <p class="text-[11px] font-black uppercase tracking-[0.16em] text-slate-400">
                Payments
            </p>

            <p class="mt-3 text-2xl font-black text-slate-900">
                {{ number_format($paymentCount) }}
            </p>
        </div>
    </div>

    <x-admin.table-card class="overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50/70 px-6 py-5">
            <div class="flex flex-col gap-1">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                    Filter Report
                </p>

                <h2 class="text-xl font-black text-slate-900">
                    Revenue Filters
                </h2>

                <p class="text-sm font-semibold text-slate-500">
                    Filter revenue by date range, payment method, or course program.
                </p>
            </div>
        </div>

        <form action="{{ route('admin.revenue.index') }}" method="GET" class="p-6">
            <div class="grid gap-4 xl:grid-cols-[180px_180px_200px_220px_auto] xl:items-end">
                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        Date From
                    </label>

                    <input
                        type="date"
                        name="date_from"
                        value="{{ $dateFrom }}"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-bold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        Date To
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        value="{{ $dateTo }}"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-bold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        Method
                    </label>

                    <select
                        name="method"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-bold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="all" @selected($method === 'all')>All Method</option>
                        <option value="manual_transfer" @selected($method === 'manual_transfer')>Manual Transfer</option>
                        <option value="cash" @selected($method === 'cash')>Cash</option>
                        <option value="other" @selected($method === 'other')>Other</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        Program
                    </label>

                    <select
                        name="program"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-bold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">All Programs</option>

                        @foreach ($programs as $programItem)
                            <option value="{{ $programItem->slug }}" @selected($program === $programItem->slug)>
                                {{ $programItem->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-black text-white shadow-sm transition hover:opacity-90">
                        Filter
                    </button>

                    @if ($dateFrom || $dateTo || $method !== 'all' || $program)
                        <a
                            href="{{ route('admin.revenue.index') }}"
                            class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-black text-slate-700 transition hover:bg-slate-50">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </x-admin.table-card>

    <div class="grid gap-6 xl:grid-cols-[1.55fr_0.75fr]">
        <x-admin.table-card class="overflow-hidden">
            <div class="border-b border-slate-200 bg-white px-6 py-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                            Revenue Trend
                        </p>

                        <h2 class="mt-1 text-xl font-black text-slate-900">
                            Monthly Revenue
                        </h2>

                        <p class="mt-1 text-sm font-semibold text-slate-500">
                            Paid payments in {{ now()->year }}.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-blue-50 px-4 py-3 text-right">
                        <p class="text-[11px] font-black uppercase tracking-[0.16em] text-blue-500">
                            Total
                        </p>

                        <p class="mt-1 text-xl font-black text-blue-700">
                            Rp {{ number_format((float) $totalRevenue, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="rounded-[26px] bg-slate-50 px-4 py-5">
                    <svg
                        viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}"
                        class="h-[360px] w-full"
                        role="img"
                        aria-label="Monthly revenue trend chart">
                        <defs>
                            <linearGradient id="revenueReportAreaGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#6FA8DC" stop-opacity="0.42" />
                                <stop offset="58%" stop-color="#6FA8DC" stop-opacity="0.16" />
                                <stop offset="100%" stop-color="#6FA8DC" stop-opacity="0" />
                            </linearGradient>

                            <filter id="revenueReportLineShadow" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="0" dy="8" stdDeviation="8" flood-color="#2563EB" flood-opacity="0.12" />
                            </filter>
                        </defs>

                        <line
                            x1="{{ $paddingLeft }}"
                            y1="{{ $baselineY }}"
                            x2="{{ $paddingLeft + $plotWidth }}"
                            y2="{{ $baselineY }}"
                            stroke="#CBD5E1"
                            stroke-width="1"
                            stroke-dasharray="6 8" />

                        <polygon
                            points="{{ $areaString }}"
                            fill="url(#revenueReportAreaGradient)" />

                        <polyline
                            points="{{ $pointString }}"
                            fill="none"
                            stroke="#6FA8DC"
                            stroke-width="6"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            filter="url(#revenueReportLineShadow)" />

                        @foreach ($activePoints as $point)
                            <circle
                                cx="{{ $point['x'] }}"
                                cy="{{ $point['y'] }}"
                                r="8"
                                fill="#6FA8DC"
                                stroke="#FFFFFF"
                                stroke-width="5" />

                            <text
                                x="{{ $point['x'] }}"
                                y="{{ max(20, $point['y'] - 20) }}"
                                text-anchor="middle"
                                font-size="13"
                                font-weight="900"
                                fill="#0F172A">
                                Rp {{ number_format((float) $point['total'], 0, ',', '.') }}
                            </text>
                        @endforeach

                        @foreach ($points as $point)
                            <text
                                x="{{ $point['x'] }}"
                                y="{{ $baselineY + 36 }}"
                                text-anchor="middle"
                                font-size="12"
                                font-weight="900"
                                fill="#94A3B8">
                                {{ $point['month'] }}
                            </text>
                        @endforeach
                    </svg>
                </div>
            </div>
        </x-admin.table-card>

        <x-admin.table-card class="overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50/70 px-6 py-5">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                    Payment Split
                </p>

                <h2 class="mt-1 text-xl font-black text-slate-900">
                    Revenue by Method
                </h2>

                <p class="mt-1 text-sm font-semibold text-slate-500">
                    Breakdown by payment method.
                </p>
            </div>

            <div class="space-y-4 p-6">
                @forelse ($revenueByMethod as $methodKey => $amount)
                    @php
                        $methodLabel = match ($methodKey) {
                            'cash' => 'Cash',
                            'other' => 'Other',
                            default => 'Manual Transfer',
                        };

                        $methodTone = match ($methodKey) {
                            'cash' => 'emerald',
                            'other' => 'amber',
                            default => 'blue',
                        };

                        $methodClasses = match ($methodTone) {
                            'emerald' => 'border-emerald-100 bg-emerald-50 text-emerald-700',
                            'amber' => 'border-amber-100 bg-amber-50 text-amber-700',
                            default => 'border-blue-100 bg-blue-50 text-blue-700',
                        };
                    @endphp

                    <div class="rounded-[24px] border p-5 {{ $methodClasses }}">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-black">
                                    {{ $methodLabel }}
                                </p>

                                <p class="mt-2 text-2xl font-black">
                                    Rp {{ number_format((float) $amount, 0, ',', '.') }}
                                </p>
                            </div>

                            <span class="rounded-full bg-white/70 px-3 py-1 text-[10px] font-black uppercase tracking-[0.12em]">
                                Paid
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[24px] border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center">
                        <h4 class="text-base font-black text-slate-900">
                            No revenue method data
                        </h4>

                        <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                            Payment method breakdown will appear here.
                        </p>
                    </div>
                @endforelse
            </div>
        </x-admin.table-card>
    </div>

    <x-admin.table-card class="overflow-hidden">
        <div class="flex flex-col gap-4 border-b border-slate-200 bg-slate-50/70 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                    Course Performance
                </p>

                <h2 class="mt-1 text-xl font-black text-slate-900">
                    Top Courses by Revenue
                </h2>

                <p class="mt-1 text-sm font-semibold text-slate-500">
                    Course contribution based on paid payments.
                </p>
            </div>

            <span class="rounded-full bg-white px-4 py-2 text-xs font-black text-slate-500 ring-1 ring-slate-200">
                {{ count($revenueByCourse) }} Courses
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="border-b border-slate-200 bg-white">
                    <tr class="text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        <th class="px-6 py-4">Course</th>
                        <th class="px-6 py-4">Revenue</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($revenueByCourse as $courseName => $amount)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5">
                                <p class="font-black text-slate-900">
                                    {{ $courseName }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-6 py-5">
                                <span class="inline-flex rounded-2xl bg-blue-50 px-4 py-2 text-sm font-black text-blue-700">
                                    Rp {{ number_format((float) $amount, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-12 text-center">
                                <h4 class="text-base font-black text-slate-900">
                                    No course revenue data yet
                                </h4>

                                <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                                    Course revenue data will appear after paid payments are recorded.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.table-card>
</section>
@endsection