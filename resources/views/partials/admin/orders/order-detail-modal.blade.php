<x-admin.modal
    model="orderModalOpen"
    title="Order Detail"
    subtitle="Review order and update status"
    size="md">
    <template x-if="selectedOrder">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Order ID
                        </p>
                        <p class="mt-2 font-bold text-[var(--color-brand-blue)]" x-text="selectedOrder.id"></p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Current Status
                        </p>

                        <span
                            class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.1em]"
                            :class="{
                                'bg-yellow-50 text-[var(--color-brand-gold)]': selectedOrder.status === 'pending',
                                'bg-blue-50 text-[var(--color-brand-blue)]': selectedOrder.status === 'approved',
                                'bg-rose-50 text-rose-600': selectedOrder.status === 'rejected'
                            }"
                            x-text="selectedOrder.statusLabel">
                        </span>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Student Name
                        </p>
                        <p class="mt-2 font-bold text-slate-900" x-text="selectedOrder.studentName"></p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Student Email
                        </p>
                        <p class="mt-2 font-bold text-slate-900" x-text="selectedOrder.studentEmail"></p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Course
                        </p>
                        <p class="mt-2 font-bold text-slate-900" x-text="selectedOrder.course"></p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Price
                        </p>
                        <p class="mt-2 font-bold text-slate-900" x-text="selectedOrder.price"></p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Order Date
                        </p>
                        <p class="mt-2 font-bold text-slate-900" x-text="selectedOrder.orderDate"></p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-base font-bold text-slate-900">
                    WhatsApp Verification
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Confirm payment via WhatsApp before approving.
                </p>

                <div class="mt-3 flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                            </svg>
                        </span>

                        <p class="font-bold text-slate-700" x-text="selectedOrder.whatsapp"></p>
                    </div>

                    <div class="flex gap-2">
                        <button
                            type="button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:bg-slate-50"
                            @click="navigator.clipboard.writeText(selectedOrder.whatsapp)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="8" y="8" width="12" height="12" rx="2" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16V6a2 2 0 012-2h10" />
                            </svg>
                        </button>

                        <a
                            :href="'https://wa.me/' + selectedOrder.whatsapp.replace(/[^0-9]/g, '')"
                            target="_blank"
                            class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-4 text-sm font-bold text-white shadow-sm transition hover:opacity-95">
                            Open WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-900">
                    Admin Note
                </label>

                <textarea
                    rows="4"
                    placeholder="Enter internal notes here..."
                    class="w-full resize-none rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100"></textarea>
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-900">
                    Reject Reason <span class="font-semibold text-slate-400">(If applicable)</span>
                </label>

                <select class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">
                    <option>Select a reason...</option>
                    <option>Payment not confirmed</option>
                    <option>Student cancelled order</option>
                    <option>Invalid enrollment data</option>
                </select>
            </div>
        </div>
    </template>

    <x-slot:footer>
        <button
            type="button"
            @click="orderModalOpen = false"
            class="h-11 rounded-xl px-5 text-sm font-bold text-slate-500 transition hover:text-slate-700">
            Cancel
        </button>

        <button
            type="button"
            @click="rejectOrder()"
            class="h-11 rounded-xl border border-rose-200 bg-white px-5 text-sm font-bold text-rose-600 transition hover:bg-rose-50">
            Reject Order
        </button>

        <button
            type="button"
            @click="approveOrder()"
            class="h-11 rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white shadow-md transition hover:opacity-95">
            Approve Order
        </button>
    </x-slot:footer>
</x-admin.modal>