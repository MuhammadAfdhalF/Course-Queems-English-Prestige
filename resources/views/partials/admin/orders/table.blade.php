<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Order ID</th>
        <th class="px-6 py-4">Student Name</th>
        <th class="px-6 py-4">Course</th>
        <th class="px-6 py-4">Price</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4">Order Date</th>
        <th class="px-6 py-4 text-right">Action</th>
    </x-slot:head>

    <template x-for="order in orders" :key="order.id">
        <tr
            x-show="matches(order)"
            x-transition.opacity.duration.200ms
            class="transition hover:bg-slate-50"
        >
            <td class="whitespace-nowrap px-6 py-5">
                <button
                    type="button"
                    @click="openOrder(order)"
                    class="font-bold text-slate-900 transition hover:text-[var(--color-brand-blue)]"
                    x-text="order.id">
                </button>
            </td>

            <td class="whitespace-nowrap px-6 py-5">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-bold"
                        :class="order.avatarColor"
                        x-text="order.studentInitials">
                    </div>

                    <div>
                        <p class="font-bold text-slate-900" x-text="order.studentName"></p>
                        <p class="mt-0.5 text-sm text-slate-400" x-text="order.studentEmail"></p>
                    </div>
                </div>
            </td>

            <td class="max-w-[220px] px-6 py-5">
                <p class="font-semibold leading-6 text-slate-700" x-text="order.course"></p>
            </td>

            <td class="whitespace-nowrap px-6 py-5 font-bold text-slate-900" x-text="order.price"></td>

            <td class="whitespace-nowrap px-6 py-5">
                <span
                    class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.1em]"
                    :class="{
                        'bg-yellow-50 text-[var(--color-brand-gold)]': order.status === 'pending',
                        'bg-blue-50 text-[var(--color-brand-blue)]': order.status === 'approved',
                        'bg-rose-50 text-rose-600': order.status === 'rejected'
                    }"
                    x-text="order.statusLabel">
                </span>
            </td>

            <td class="whitespace-nowrap px-6 py-5 font-semibold text-slate-500" x-text="order.orderDate"></td>

            <td class="whitespace-nowrap px-6 py-5 text-right">
                <button
                    type="button"
                    @click="openOrder(order)"
                    class="inline-flex h-10 items-center justify-center rounded-xl bg-emerald-50 px-4 text-sm font-bold text-emerald-600 transition hover:bg-emerald-100">
                    Review
                </button>
            </td>
        </tr>
    </template>

    <tr x-show="orders.filter(order => matches(order)).length === 0">
        <td colspan="7" class="px-6 py-12 text-center text-sm font-medium text-slate-400">
            No orders match your current filter.
        </td>
    </tr>

    <x-slot:footer>
        <x-admin.pagination text="Showing 1-10 of 910 orders" />
    </x-slot:footer>
</x-admin.data-table>