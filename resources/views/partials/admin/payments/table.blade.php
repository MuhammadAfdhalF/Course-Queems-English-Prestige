<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Payment Date</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Student</th>
        <th class="px-6 py-4">Course</th>
        <th class="px-6 py-4">Amount</th>
        <th class="px-6 py-4">Method</th>
        <th class="px-6 py-4">Status</th>
        <th class="w-[160px] px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($payments as $payment)
    @php
    $statusVariant = match ($payment->payment_status) {
    'paid' => 'completed',
    'cancelled' => 'rejected',
    default => 'pending',
    };

    $methodLabel = match ($payment->payment_method) {
    'cash' => 'Cash',
    'other' => 'Other',
    default => 'Manual Transfer',
    };
    @endphp

    <tr class="text-sm text-slate-700 transition hover:bg-slate-50/80">
        <td class="whitespace-nowrap px-6 py-5 font-semibold text-slate-600">
            {{ $payment->payment_date?->format('d M Y H:i') ?? '-' }}
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            <p class="font-extrabold text-slate-900">
                {{ $payment->order?->order_code ?? '-' }}
            </p>
        </td>

        <td class="min-w-[220px] px-6 py-5">
            <p class="font-extrabold text-slate-900">
                {{ $payment->student?->name ?? 'Unknown Student' }}
            </p>
            <p class="mt-1 text-xs font-semibold text-slate-400">
                {{ $payment->student?->email ?? '-' }}
            </p>
        </td>

        <td class="min-w-[220px] px-6 py-5">
            <p class="font-bold text-slate-900">
                {{ $payment->courseLevel?->name ?? 'Unknown Course' }}
            </p>
            <p class="mt-1 text-xs font-semibold text-slate-400">
                {{ $payment->courseLevel?->courseProgram?->name ?? '-' }}
            </p>
        </td>

        <td class="whitespace-nowrap px-6 py-5 font-extrabold text-slate-900">
            Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
        </td>

        <td class="whitespace-nowrap px-6 py-5 font-semibold text-slate-600">
            {{ $methodLabel }}
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            <x-admin.status-badge :variant="$statusVariant">
                {{ ucfirst($payment->payment_status) }}
            </x-admin.status-badge>
        </td>

        <td class="px-6 py-5">
            <div class="flex justify-center">
                <a
                    href="{{ route('admin.payments.show', $payment) }}"
                    class="inline-flex h-10 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-xs font-bold text-white transition hover:opacity-90">
                    View Detail
                </a>
            </div>
        </td>
    </tr>
    @empty
    <x-admin.empty-state
        colspan="8"
        title="No payments found"
        description="Payments will appear after admin records payment from pending orders." />
    @endforelse

    <x-slot:footer>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm font-semibold text-slate-500">
                Showing {{ $payments->firstItem() ?? 0 }} - {{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }} payments
            </p>

            <div>
                {{ $payments->links() }}
            </div>
        </div>
    </x-slot:footer>
</x-admin.data-table>