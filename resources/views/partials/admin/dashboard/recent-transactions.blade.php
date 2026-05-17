<x-admin.table-card>
    <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-900">
                Recent Transactions
            </h3>

            <p class="mt-1 text-sm text-slate-500">
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
            <thead class="border-b border-slate-200 bg-slate-50">
                <tr class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                    <th class="px-6 py-4">Order ID</th>
                    <th class="px-6 py-4">Student</th>
                    <th class="px-6 py-4">Course</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse ($transactions as $transaction)
                <tr class="transition hover:bg-slate-50">
                    <td class="whitespace-nowrap px-6 py-5 text-slate-500">
                        {{ $transaction['orderId'] }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 font-semibold text-slate-900">
                        {{ $transaction['student'] }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 text-slate-900">
                        {{ $transaction['course'] }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 font-semibold text-slate-900">
                        {{ $transaction['price'] }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        <x-admin.status-badge :variant="$transaction['statusVariant']">
                            {{ $transaction['status'] }}
                        </x-admin.status-badge>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 text-slate-500">
                        {{ $transaction['date'] }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        <a
                            href="{{ $transaction['href'] }}"
                            class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <h4 class="text-base font-extrabold text-slate-900">
                            No transactions yet
                        </h4>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Student course orders will appear here.
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex flex-col gap-4 border-t border-slate-200 px-6 py-5 text-sm text-slate-500 lg:flex-row lg:items-center lg:justify-between">
        <p>
            Showing {{ count($transactions) }} of {{ $totalTransactions }} transactions
        </p>

        <a
            href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            View All Orders
        </a>
    </div>
</x-admin.table-card>