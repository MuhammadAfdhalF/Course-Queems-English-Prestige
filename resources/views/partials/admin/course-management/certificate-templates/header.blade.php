<x-admin.page-toolbar
    :back-url="route('admin.dashboard')"
    back-label="Back to Dashboard">
    <x-slot:actions>
        <button
            type="button"
            @click="createModalOpen = true"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Add Template</span>
        </button>
    </x-slot:actions>
</x-admin.page-toolbar>

<x-admin.table-card class="p-6">
    <div class="grid gap-6 xl:grid-cols-[1fr_auto] xl:items-start">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                Certificate Templates
            </p>

            <h2 class="mt-2 text-2xl font-bold text-slate-900">
                Template Library
            </h2>

            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                Manage reusable certificate backgrounds for global use or specific course programs. Active default templates are used automatically when certificates are generated.
            </p>

            <div class="mt-5 grid gap-3 lg:grid-cols-2">
                <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3">
                    <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-500">
                        Global Template
                    </p>

                    <p class="mt-2 text-sm leading-6 text-blue-700">
                        Used as a fallback when a course program does not have its own active default template.
                    </p>
                </div>

                <div class="rounded-2xl border border-amber-100 bg-amber-50 px-4 py-3">
                    <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-amber-500">
                        Program Template
                    </p>

                    <p class="mt-2 text-sm leading-6 text-amber-700">
                        Used for certificates under the selected course program when marked as default and active.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-3 sm:grid-cols-3 xl:min-w-[360px]">
            <div class="rounded-2xl bg-slate-50 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">
                    Total
                </p>
                <p class="mt-1 text-2xl font-extrabold text-slate-900">
                    {{ $templates->count() }}
                </p>
            </div>

            <div class="rounded-2xl bg-emerald-50 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-emerald-500">
                    Active
                </p>
                <p class="mt-1 text-2xl font-extrabold text-emerald-700">
                    {{ $templates->where('is_active', true)->count() }}
                </p>
            </div>

            <div class="rounded-2xl bg-yellow-50 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-[var(--color-brand-gold)]">
                    Default
                </p>
                <p class="mt-1 text-2xl font-extrabold text-[var(--color-brand-gold)]">
                    {{ $templates->where('is_default', true)->count() }}
                </p>
            </div>
        </div>
    </div>
</x-admin.table-card>