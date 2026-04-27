<x-admin.modal
    model="orderModalOpen"
    title="Order Detail"
    subtitle="Review order and update status"
    size="md">
    <template x-if="selectedOrder">
        <div class="space-y-5">
            {{-- ORDER SUMMARY --}}
            <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5">
                <div class="mb-4 flex items-start justify-between gap-4 border-b border-slate-200 pb-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Order ID
                        </p>

                        <p class="mt-1 text-lg font-extrabold text-[var(--color-brand-blue)]" x-text="selectedOrder.id"></p>
                    </div>

                    <span
                        class="inline-flex rounded-full px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.14em]"
                        :class="{
                            'bg-yellow-50 text-[var(--color-brand-gold)] ring-1 ring-yellow-100': selectedOrder.status === 'pending',
                            'bg-blue-50 text-[var(--color-brand-blue)] ring-1 ring-blue-100': selectedOrder.status === 'approved',
                            'bg-rose-50 text-rose-600 ring-1 ring-rose-100': selectedOrder.status === 'rejected'
                        }"
                        x-text="selectedOrder.statusLabel">
                    </span>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            Student Name
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-900" x-text="selectedOrder.studentName"></p>
                    </div>

                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            Student Email
                        </p>
                        <p class="mt-1 break-all text-sm font-bold text-slate-900" x-text="selectedOrder.studentEmail"></p>
                    </div>

                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            Course
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-900" x-text="selectedOrder.course"></p>
                    </div>

                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            Price
                        </p>
                        <p class="mt-1 text-sm font-extrabold text-slate-900" x-text="selectedOrder.price"></p>
                    </div>

                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            Order Date
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-900" x-text="selectedOrder.orderDate"></p>
                    </div>
                </div>
            </div>

            {{-- WHATSAPP VERIFICATION --}}
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/40 p-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                            </svg>
                        </span>

                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">
                                WhatsApp Verification
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                Confirm payment with the student before approving this order.
                            </p>

                            <p class="mt-2 text-base font-extrabold text-emerald-700" x-text="selectedOrder.whatsapp"></p>
                        </div>
                    </div>

                    <div class="flex shrink-0 gap-2">
                        <button
                            type="button"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-emerald-200 bg-white text-emerald-600 transition hover:bg-emerald-50"
                            @click="navigator.clipboard.writeText(selectedOrder.whatsapp)"
                            title="Copy WhatsApp number">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="8" y="8" width="12" height="12" rx="2" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16V6a2 2 0 012-2h10" />
                            </svg>
                        </button>

                        <a
                            :href="'https://wa.me/' + selectedOrder.whatsapp.replace(/[^0-9]/g, '')"
                            target="_blank"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 text-sm font-extrabold text-white shadow-sm transition hover:bg-emerald-700">
                            Open WA
                        </a>
                    </div>
                </div>
            </div>

            {{-- ADMIN NOTE --}}
            <div>
                <label class="mb-2 block text-sm font-extrabold text-slate-900">
                    Admin Note
                </label>

                <textarea
                    rows="3"
                    placeholder="Enter internal notes here..."
                    class="w-full resize-none rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100"></textarea>
            </div>

            {{-- REJECT REASON --}}
            <div>
                <label class="mb-2 block text-sm font-extrabold text-slate-900">
                    Reject Reason
                    <span class="font-semibold text-slate-400">(if applicable)</span>
                </label>

                <select class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-600 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">
                    <option>Select a reason...</option>
                    <option>Payment not confirmed</option>
                    <option>Student cancelled order</option>
                    <option>Invalid enrollment data</option>
                    <option>Duplicate order</option>
                </select>
            </div>
        </div>
    </template>

    <x-slot:footer>
        <button
            type="button"
            @click="orderModalOpen = false"
            class="h-11 rounded-xl px-5 text-sm font-extrabold text-slate-500 transition hover:text-slate-700">
            Cancel
        </button>

        <button
            type="button"
            @click="rejectOrder()"
            class="h-11 rounded-xl border border-rose-200 bg-white px-5 text-sm font-extrabold text-rose-600 transition hover:bg-rose-50">
            Reject Order
        </button>

        <button
            type="button"
            @click="approveOrder()"
            class="h-11 rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-extrabold text-white shadow-md transition hover:opacity-95">
            Approve Order
        </button>
    </x-slot:footer>
</x-admin.modal>