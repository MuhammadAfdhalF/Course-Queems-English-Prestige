<div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-2xs">
    <div class="flex flex-col gap-3 border-b border-slate-100 bg-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
            <h3 class="text-base font-bold text-slate-900">
                Recent Transactions
            </h3>
            <p class="text-xs font-medium text-slate-500">
                Latest course orders and approval status.
            </p>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="inline-flex h-8 items-center justify-center rounded-lg border border-slate-200/80 bg-white px-3 text-xs font-bold text-slate-700 shadow-2xs transition hover:bg-slate-50 hover:text-[#080D4D]">
            View All Orders
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead class="border-b border-slate-100 bg-slate-50/60">
                <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <th class="px-5 py-3 sm:px-6">Order ID</th>
                    <th class="px-5 py-3 sm:px-6">Student</th>
                    <th class="px-5 py-3 sm:px-6">Course</th>
                    <th class="px-5 py-3 sm:px-6">Price</th>
                    <th class="px-5 py-3 sm:px-6">Status</th>
                    <th class="px-5 py-3 sm:px-6">Date</th>
                    <th class="px-5 py-3 sm:px-6 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($transactions as $transaction)
                <tr class="transition-colors hover:bg-indigo-50/20">
                    <td class="whitespace-nowrap px-5 py-3.5 sm:px-6">
                        <p class="text-xs font-bold text-slate-900">
                            {{ $transaction['orderId'] }}
                        </p>
                    </td>

                    <td class="whitespace-nowrap px-5 py-3.5 sm:px-6">
                        <p class="text-xs font-bold text-slate-900">
                            {{ $transaction['student'] }}
                        </p>
                    </td>

                    <td class="min-w-[180px] px-5 py-3.5 sm:px-6">
                        <p class="line-clamp-1 text-xs font-medium text-slate-700">
                            {{ $transaction['course'] }}
                        </p>
                    </td>

                    <td class="whitespace-nowrap px-5 py-3.5 sm:px-6 text-xs font-bold tabular-nums text-slate-900">
                        {{ $transaction['price'] }}
                    </td>

                    <td class="whitespace-nowrap px-5 py-3.5 sm:px-6">
                        <x-admin.status-badge :variant="$transaction['statusVariant']">
                            {{ $transaction['status'] }}
                        </x-admin.status-badge>
                    </td>

                    <td class="whitespace-nowrap px-5 py-3.5 sm:px-6 text-xs font-medium text-slate-500">
                        {{ $transaction['date'] }}
                    </td>

                    <td class="whitespace-nowrap px-5 py-3.5 sm:px-6 text-right">
                        <a
                            href="{{ $transaction['href'] }}"
                            class="inline-flex h-7 items-center justify-center rounded-lg border border-slate-200/80 bg-white px-2.5 text-[11px] font-bold text-slate-700 shadow-2xs transition hover:bg-slate-50 hover:text-[#080D4D]">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center">
                        <h4 class="text-xs font-bold text-slate-900">
                            No transactions yet
                        </h4>
                        <p class="mt-0.5 text-[11px] font-medium text-slate-500">
                            Student course orders will appear here.
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/40 px-5 py-3.5 text-xs font-medium text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <p>
            Showing {{ count($transactions) }} of {{ $totalTransactions }} transactions
        </p>

        <a
            href="{{ route('admin.orders.index') }}"
            class="inline-flex h-8 items-center justify-center rounded-lg bg-[#080D4D] px-3.5 text-xs font-bold text-white shadow-2xs transition hover:bg-[#060A3B]">
            Manage Orders
        </a>
    </div>
</div>