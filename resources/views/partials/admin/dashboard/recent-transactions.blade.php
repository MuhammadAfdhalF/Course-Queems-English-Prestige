<x-admin.table-card class="overflow-hidden">
    <div class="flex flex-col gap-4 border-b border-slate-200 bg-slate-50/70 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                Orders
            </p>

            <h3 class="mt-1 text-2xl font-black text-slate-900">
                Recent Transactions
            </h3>

            <p class="mt-1 text-sm font-semibold text-slate-500">
                Latest course orders and approval status.
            </p>
        </div>

        <a href="{{ route('admin.orders.index') }}">
            <x-ui.button variant="outline">
                View All Orders
            </x-ui.button>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead class="border-b border-slate-200 bg-white">
                <tr class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                    <th class="px-6 py-4">Order ID</th>
                    <th class="px-6 py-4">Student</th>
                    <th class="px-6 py-4">Course</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($transactions as $transaction)
                <tr class="transition hover:bg-slate-50/80">
                    <td class="whitespace-nowrap px-6 py-5">
                        <p class="font-black text-slate-900">
                            {{ $transaction['orderId'] }}
                        </p>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        <p class="font-bold text-slate-900">
                            {{ $transaction['student'] }}
                        </p>
                    </td>

                    <td class="min-w-[220px] px-6 py-5">
                        <p class="line-clamp-1 font-semibold text-slate-700">
                            {{ $transaction['course'] }}
                        </p>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 font-black text-slate-900">
                        {{ $transaction['price'] }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        <x-admin.status-badge :variant="$transaction['statusVariant']">
                            {{ $transaction['status'] }}
                        </x-admin.status-badge>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 text-sm font-semibold text-slate-500">
                        {{ $transaction['date'] }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        <a
                            href="{{ $transaction['href'] }}"
                            class="inline-flex h-9 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-black text-slate-700 transition hover:bg-slate-50">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <h4 class="text-base font-black text-slate-900">
                            No transactions yet
                        </h4>

                        <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                            Student course orders will appear here.
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex flex-col gap-4 border-t border-slate-200 bg-slate-50/60 px-6 py-5 text-sm font-semibold text-slate-500 lg:flex-row lg:items-center lg:justify-between">
        <p>
            Showing {{ count($transactions) }} of {{ $totalTransactions }} transactions
        </p>

        <a
            href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-2.5 text-sm font-black text-white transition hover:opacity-90">
            Manage Orders
        </a>
    </div>
</x-admin.table-card>