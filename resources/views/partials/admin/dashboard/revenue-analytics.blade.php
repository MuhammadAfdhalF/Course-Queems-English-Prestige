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

<x-admin.table-card class="overflow-hidden">
    <div class="border-b border-slate-200 bg-white px-6 py-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                    Financial Overview
                </p>

                <h3 class="mt-1 text-2xl font-black text-slate-900">
                    Revenue Analytics
                </h3>

                <p class="mt-1 text-sm font-semibold text-slate-500">
                    Revenue trend from paid course orders in {{ $currentYear }}.
                </p>
            </div>

            <a
                href="{{ route('admin.revenue.index') }}"
                class="inline-flex h-10 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-xs font-black text-white transition hover:opacity-90">
                Open Report
            </a>
        </div>
    </div>

    <div class="grid gap-5 p-6 xl:grid-cols-[1.7fr_0.65fr]">
        <div class="rounded-[28px] border border-slate-200 bg-white p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                        Revenue Trend
                    </p>

                    <p class="mt-3 text-4xl font-black leading-tight text-slate-950 lg:text-5xl">
                        Rp {{ number_format((float) $yearToDateRevenue, 0, ',', '.') }}
                    </p>

                    <p class="mt-2 text-sm font-bold text-slate-500">
                        Year to date revenue
                    </p>
                </div>

                <div class="rounded-2xl bg-blue-50 px-4 py-3 text-right">
                    <p class="text-[11px] font-black uppercase tracking-[0.16em] text-blue-500">
                        This Month
                    </p>

                    <p class="mt-1 text-xl font-black text-blue-700">
                        Rp {{ number_format((float) $thisMonthRevenue, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="mt-6 rounded-[26px] bg-slate-50 px-4 py-5">
                <svg
                    viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}"
                    class="h-[360px] w-full"
                    role="img"
                    aria-label="Revenue trend chart">
                    <defs>
                        <linearGradient id="revenueAreaGradientMinimal" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#6FA8DC" stop-opacity="0.42" />
                            <stop offset="58%" stop-color="#6FA8DC" stop-opacity="0.16" />
                            <stop offset="100%" stop-color="#6FA8DC" stop-opacity="0" />
                        </linearGradient>

                        <filter id="revenueLineShadowMinimal" x="-20%" y="-20%" width="140%" height="140%">
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
                        fill="url(#revenueAreaGradientMinimal)" />

                    <polyline
                        points="{{ $pointString }}"
                        fill="none"
                        stroke="#6FA8DC"
                        stroke-width="6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        filter="url(#revenueLineShadowMinimal)" />

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

                    @foreach ($points as $index => $point)
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

        <div class="space-y-5">
            <div class="rounded-[26px] border border-emerald-100 bg-emerald-50 p-6">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-emerald-500">
                    This Month
                </p>

                <p class="mt-4 text-3xl font-black leading-tight text-emerald-700">
                    Rp {{ number_format((float) $thisMonthRevenue, 0, ',', '.') }}
                </p>

                <p class="mt-3 text-sm font-bold leading-6 text-emerald-600/80">
                    Paid revenue this month.
                </p>
            </div>

            <div class="rounded-[26px] border border-blue-100 bg-blue-50 p-6">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-500">
                    Year to Date
                </p>

                <p class="mt-4 text-3xl font-black leading-tight text-blue-700">
                    Rp {{ number_format((float) $yearToDateRevenue, 0, ',', '.') }}
                </p>

                <p class="mt-3 text-sm font-bold leading-6 text-blue-600/80">
                    Total revenue in {{ $currentYear }}.
                </p>
            </div>
        </div>
    </div>
</x-admin.table-card>