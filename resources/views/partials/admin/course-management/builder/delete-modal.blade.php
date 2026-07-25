<div
    x-show="deleteModalOpen"
    x-cloak
    class="relative z-50"
    role="dialog"
    aria-modal="true"
    @keydown.window.escape="deleteModalOpen = false">

    {{-- Backdrop --}}
    <div
        x-show="deleteModalOpen"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="deleteModalOpen = false"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                x-show="deleteModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900" x-text="deleteModalTitle"></h3>
                            <p class="text-xs text-slate-500 mt-0.5">This action cannot be undone.</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl border border-rose-100 bg-rose-50/50 p-3 text-xs text-slate-700">
                        <span>Are you sure you want to delete </span>
                        <strong class="font-extrabold text-rose-900" x-text="deleteModalItemTitle"></strong>?
                    </div>

                    <template x-if="deleteModalError">
                        <div class="mt-3 rounded-xl border border-rose-200 bg-rose-100 p-3 text-xs font-bold text-rose-800" x-text="deleteModalError"></div>
                    </template>
                </div>

                <div class="border-t border-slate-100 bg-slate-50 px-6 py-4 flex items-center justify-end gap-3">
                    <button
                        type="button"
                        @click="deleteModalOpen = false"
                        class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-100 transition">
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="executeDelete()"
                        :disabled="deleteModalDeleting"
                        class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-rose-700 transition disabled:opacity-50">
                        <template x-if="deleteModalDeleting">
                            <div class="h-3.5 w-3.5 animate-spin rounded-full border-2 border-white border-t-transparent"></div>
                        </template>
                        <span x-text="deleteModalDeleting ? 'Deleting...' : 'Confirm Delete'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
