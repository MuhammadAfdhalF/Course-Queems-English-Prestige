@php
$chartWidth = 760;
$chartHeight = 240;

$paddingLeft = 28;
$paddingRight = 28;
$paddingTop = 28;
$paddingBottom = 40;

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

<div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-2xs">
    <div class="border-b border-slate-100 bg-white px-5 py-4 sm:px-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-slate-900">
                    Revenue Analytics
                </h3>
                <p class="text-xs font-medium text-slate-500">
                    Paid revenue trend in {{ $currentYear }}.
                </p>
            </div>

            <a
                href="{{ route('admin.revenue.index') }}"
                class="inline-flex h-8 items-center justify-center rounded-lg border border-slate-200/80 bg-white px-3 text-xs font-bold text-slate-700 shadow-2xs transition hover:bg-slate-50 hover:text-[#080D4D]">
                Open Report
            </a>
        </div>
    </div>

    <div class="p-4 sm:p-5">
        {{-- Revenue Summary Header Bar --}}
        <div class="flex flex-wrap items-end justify-between gap-4 rounded-xl border border-slate-100 bg-slate-50/60 p-4">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Year to Date Revenue
                </p>
                <p class="mt-0.5 text-2xl sm:text-3xl font-black text-[#080D4D] tracking-tight">
                    Rp {{ number_format((float) $yearToDateRevenue, 0, ',', '.') }}
                </p>
            </div>

            <div class="inline-flex items-center gap-2 rounded-lg border border-amber-200/80 bg-amber-50/60 px-3 py-1.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#AD6B10]">This Month:</span>
                <span class="text-xs font-extrabold text-[#AD6B10]">Rp {{ number_format((float) $thisMonthRevenue, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- SVG Revenue Chart --}}
        <div class="mt-4 rounded-xl border border-slate-100 bg-slate-50/40 p-2 sm:p-3">
            <svg
                viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}"
                class="h-[210px] sm:h-[230px] w-full"
                role="img"
                aria-label="Revenue trend chart">
                <defs>
                    <linearGradient id="revenueAreaGradientMinimal" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#080D4D" stop-opacity="0.16" />
                        <stop offset="70%" stop-color="#080D4D" stop-opacity="0.02" />
                        <stop offset="100%" stop-color="#080D4D" stop-opacity="0" />
                    </linearGradient>
                </defs>

                <line
                    x1="{{ $paddingLeft }}"
                    y1="{{ $baselineY }}"
                    x2="{{ $paddingLeft + $plotWidth }}"
                    y2="{{ $baselineY }}"
                    stroke="#E2E8F0"
                    stroke-width="1"
                    stroke-dasharray="4 6" />

                <polygon
                    points="{{ $areaString }}"
                    fill="url(#revenueAreaGradientMinimal)" />

                <polyline
                    points="{{ $pointString }}"
                    fill="none"
                    stroke="#080D4D"
                    stroke-width="3.5"
                    stroke-linecap="round"
                    stroke-linejoin="round" />

                @foreach ($activePoints as $point)
                <circle
                    cx="{{ $point['x'] }}"
                    cy="{{ $point['y'] }}"
                    r="5"
                    fill="#AD6B10"
                    stroke="#FFFFFF"
                    stroke-width="2.5" />

                <text
                    x="{{ $point['x'] }}"
                    y="{{ max(16, $point['y'] - 12) }}"
                    text-anchor="middle"
                    font-size="10"
                    font-weight="700"
                    fill="#080D4D">
                    Rp {{ number_format((float) $point['total'], 0, ',', '.') }}
                </text>
                @endforeach

                @foreach ($points as $index => $point)
                <text
                    x="{{ $point['x'] }}"
                    y="{{ $baselineY + 22 }}"
                    text-anchor="middle"
                    font-size="10"
                    font-weight="600"
                    fill="#64748B">
                    {{ $point['month'] }}
                </text>
                @endforeach
            </svg>
        </div>
    </div>
</div>