<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Order ID</th>
        <th class="px-6 py-4">Student Name</th>
        <th class="px-6 py-4">Course</th>
        <th class="px-6 py-4">Price</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4">Order Date</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    <template x-for="order in orders" :key="order.id">
        <tr
            x-show="matches(order)"
            x-transition.opacity.duration.200ms
            class="transition hover:bg-slate-50">
            <td class="whitespace-nowrap px-6 py-5">
                <button
                    type="button"
                    @click="openOrder(order)"
                    class="max-w-[78px] text-left text-base font-extrabold leading-tight text-slate-900 transition hover:text-[var(--color-brand-blue)]"
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
                        <p class="text-base font-bold text-slate-900" x-text="order.studentName"></p>
                        <p class="mt-0.5 text-sm font-medium text-slate-400" x-text="order.studentEmail"></p>
                    </div>
                </div>
            </td>

            <td class="max-w-[190px] px-6 py-5">
                <p class="text-base font-bold leading-6 text-slate-700" x-text="order.course"></p>
            </td>

            <td class="whitespace-nowrap px-6 py-5 text-base font-extrabold text-slate-900" x-text="order.price"></td>

            <td class="whitespace-nowrap px-6 py-5">
                <span
                    class="inline-flex rounded-full px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.14em]"
                    :class="{
                        'bg-yellow-50 text-[var(--color-brand-gold)] ring-1 ring-yellow-100': order.status === 'pending',
                        'bg-blue-50 text-[var(--color-brand-blue)] ring-1 ring-blue-100': order.status === 'approved',
                        'bg-rose-50 text-rose-600 ring-1 ring-rose-100': order.status === 'rejected'
                    }"
                    x-text="order.statusLabel">
                </span>
            </td>

            <td class="whitespace-nowrap px-6 py-5 text-base font-bold leading-tight text-slate-500" x-text="order.orderDate"></td>

            <td class="whitespace-nowrap px-6 py-5 text-center">
                <button
                    type="button"
                    @click="openOrder(order)"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition hover:bg-emerald-100"
                    aria-label="Review order">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                    </svg>
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