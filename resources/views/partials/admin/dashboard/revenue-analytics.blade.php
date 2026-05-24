<x-admin.table-card class="overflow-hidden">
    <div class="border-b border-slate-200 px-6 py-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                    Financial Overview
                </p>

                <h3 class="mt-1 text-2xl font-black text-slate-900">
                    Revenue Analytics
                </h3>

                <p class="mt-1 text-sm font-semibold text-slate-500">
                    Monthly approved order revenue in {{ $currentYear }}.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-black text-blue-700">
                    Monthly
                </span>

                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-600">
                    {{ $currentYear }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid gap-6 p-6 lg:grid-cols-[1.75fr_0.85fr]">
      
        <div class="rounded-[26px] border border-slate-200 bg-slate-50 p-5">
            <div class="flex h-72 items-end gap-3">
                @foreach ($monthlyRevenue as $month)
                @php
                $barHeight = max(16, (int) $month['height'] * 2);
                $hasRevenue = (float) $month['total'] > 0;
                @endphp

                <div class="group flex h-full flex-1 flex-col items-center justify-end gap-2">
                    <div class="flex h-60 w-full items-end">
                        <div
                            title="{{ $month['month'] }}: Rp {{ number_format((float) $month['total'], 0, ',', '.') }}"
                            class="w-full rounded-t-2xl transition duration-300 group-hover:opacity-80 {{ $hasRevenue ? 'bg-[var(--color-brand-blue)]' : 'bg-slate-200' }}"
                            style="height: {{ $barHeight }}px;">
                        </div>
                    </div>

                    <span class="text-[10px] font-black uppercase text-slate-400">
                        {{ $month['month'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-[24px] border border-emerald-100 bg-emerald-50 p-5">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-emerald-500">
                    This Month
                </p>

                <p class="mt-3 text-3xl font-black leading-tight text-emerald-700">
                    Rp {{ number_format((float) $thisMonthRevenue, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-sm font-bold text-emerald-600/80">
                    Approved orders this month
                </p>
            </div>

            <div class="rounded-[24px] border border-blue-100 bg-blue-50 p-5">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-500">
                    Year to Date
                </p>

                <p class="mt-3 text-3xl font-black leading-tight text-blue-700">
                    Rp {{ number_format((float) $yearToDateRevenue, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-sm font-bold text-blue-600/80">
                    Total revenue in {{ $currentYear }}
                </p>
            </div>

            <a
                href="{{ route('admin.revenue.index') }}"
                class="inline-flex h-12 w-full items-center justify-center rounded-2xl bg-[var(--color-brand-blue)] px-5 text-sm font-black text-white transition hover:opacity-90">
                Open Revenue Report
            </a>
        </div>
    </div>
</x-admin.table-card>