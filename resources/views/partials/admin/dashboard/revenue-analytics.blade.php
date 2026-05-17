<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-2xl font-bold text-slate-900">
                    Revenue Analytics
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Monthly approved order revenue in {{ $currentYear }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <x-admin.status-badge variant="approved">
                    Monthly
                </x-admin.status-badge>

                <x-admin.status-badge>
                    {{ $currentYear }}
                </x-admin.status-badge>
            </div>
        </div>
    </div>

    <div class="grid gap-6 p-6 lg:grid-cols-[2fr_1fr]">
        <div class="rounded-2xl bg-slate-50 p-6">
            <div class="flex h-72 items-end gap-3">
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

        <div class="space-y-6">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                    This Month
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    Rp {{ number_format((float) $thisMonthRevenue, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-sm font-semibold text-emerald-600">
                    Approved orders this month
                </p>
            </div>

            <div class="border-t border-slate-200 pt-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                    Year to Date (YTD)
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    Rp {{ number_format((float) $yearToDateRevenue, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-sm font-semibold text-slate-500">
                    Based on approved course orders
                </p>
            </div>
        </div>
    </div>
</x-admin.table-card>