<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-2xl font-bold text-slate-900">
                    Revenue Analytics
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Monthly breakdown of income streams
                </p>
            </div>

            <div class="flex items-center gap-3">
                <x-admin.status-badge variant="approved">
                    Monthly
                </x-admin.status-badge>

                <x-admin.status-badge>
                    2024
                </x-admin.status-badge>
            </div>
        </div>
    </div>

    <div class="grid gap-6 p-6 lg:grid-cols-[2fr_1fr]">
        <div class="flex h-72 items-end gap-4 rounded-2xl bg-slate-50 p-6">
            <div class="h-24 w-full rounded-t-xl bg-blue-100"></div>
            <div class="h-32 w-full rounded-t-xl bg-blue-100"></div>
            <div class="h-44 w-full rounded-t-xl bg-blue-100"></div>
            <div class="h-28 w-full rounded-t-xl bg-blue-100"></div>
            <div class="h-40 w-full rounded-t-xl bg-blue-100"></div>
            <div class="h-56 w-full rounded-t-xl bg-[var(--color-brand-blue)]"></div>
            <div class="h-10 w-full rounded-t-xl bg-blue-100"></div>
        </div>

        <div class="space-y-6">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                    This Month
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    Rp 128,450,000
                </p>

                <p class="mt-2 text-sm font-semibold text-emerald-600">
                    ↑14% vs last month
                </p>
            </div>

            <div class="border-t border-slate-200 pt-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                    Year to Date (YTD)
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    Rp 842,900,000
                </p>

                <p class="mt-2 text-sm font-semibold text-slate-500">
                    Targets: 84% reached
                </p>
            </div>
        </div>
    </div>
</x-admin.table-card>