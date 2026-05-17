<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-xl font-black text-slate-900">
            Order History
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Course orders submitted by this student.
        </p>
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
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($orders as $order)
                <tr>
                    <td class="whitespace-nowrap px-6 py-5">
                        <p class="font-bold text-slate-900">
                            {{ $order->order_code }}
                        </p>
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
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                        No orders yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin.table-card>