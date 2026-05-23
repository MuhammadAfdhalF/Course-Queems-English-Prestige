<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-900">
                    Order History
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Course orders submitted by this student.
                </p>
            </div>

            <a
                href="{{ route('admin.orders.index') }}"
                class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-black text-slate-600 transition hover:bg-slate-50">
                View All Orders
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead class="border-b border-slate-200 bg-slate-50">
                <tr class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Course</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($orders as $order)
                <tr class="transition hover:bg-slate-50/70">
                    <td class="whitespace-nowrap px-6 py-5">
                        <a
                            href="{{ route('admin.orders.show', $order) }}"
                            class="font-black text-[var(--color-brand-blue)] hover:underline">
                            {{ $order->order_code }}
                        </a>
                    </td>

                    <td class="min-w-[220px] px-6 py-5">
                        <p class="font-bold text-slate-900">
                            {{ $order->courseLevel?->name ?? 'Unknown Course' }}
                        </p>
                        <p class="mt-1 text-xs text-slate-400">
                            {{ $order->courseLevel?->courseProgram?->name ?? '-' }}
                        </p>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 font-bold text-slate-900">
                        Rp {{ number_format((float) $order->price, 0, ',', '.') }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        @php
                        $variant = match ($order->status) {
                        'approved' => 'completed',
                        'rejected' => 'rejected',
                        default => 'pending',
                        };
                        @endphp

                        <x-admin.status-badge :variant="$variant">
                            {{ ucfirst($order->status) }}
                        </x-admin.status-badge>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-500">
                        {{ $order->order_date?->format('d M Y') ?? '-' }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 text-right">
                        <a
                            href="{{ route('admin.orders.show', $order) }}"
                            class="inline-flex h-9 items-center justify-center rounded-lg bg-[var(--color-brand-blue)] px-4 text-xs font-black text-white transition hover:opacity-90">
                            View Order
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <p class="text-sm font-black text-slate-700">No orders yet</p>
                        <p class="mt-1 text-sm text-slate-500">
                            Course orders from this student will appear here.
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin.table-card>