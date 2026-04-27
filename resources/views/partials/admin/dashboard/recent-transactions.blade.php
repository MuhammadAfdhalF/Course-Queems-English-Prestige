<x-admin.table-card>
    <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-2xl font-bold text-slate-900">
            Recent Transactions
        </h3>

        <x-ui.button variant="outline">
            Export CSV
        </x-ui.button>
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
                @foreach ($transactions as $transaction)
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
                            <x-admin.table-action-button />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex flex-col gap-4 border-t border-slate-200 px-6 py-5 text-sm text-slate-500 lg:flex-row lg:items-center lg:justify-between">
        <p>
            Showing 4 of 856 transactions
        </p>

        <div class="flex items-center gap-3">
            <x-ui.button variant="outline" class="px-4 py-2">
                Previous
            </x-ui.button>

            <x-ui.button variant="outline" class="px-4 py-2">
                Next
            </x-ui.button>
        </div>
    </div>
</x-admin.table-card>