<x-admin.page-toolbar
    :back-url="route('admin.cms.home.index')"
    back-label="Back to CMS">
    <x-slot:actions>
        <div class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 shadow-sm">
            {{ $testimonials->count() }} Testimonials
        </div>
    </x-slot:actions>
</x-admin.page-toolbar>

<x-admin.table-card class="p-6">
    <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                Testimonial Moderation
            </p>

            <h2 class="mt-2 text-2xl font-bold text-slate-900">
                Student Testimonials
            </h2>

            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                Review testimonials submitted by students. Publishing or featuring testimonials only affects public website visibility and does not affect certificates.
            </p>
        </div>

        <div class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-2xl bg-slate-50 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">
                    Awaiting
                </p>
                <p class="mt-1 text-2xl font-extrabold text-slate-900">
                    {{ $testimonials->where('is_active', false)->count() }}
                </p>
            </div>

            <div class="rounded-2xl bg-emerald-50 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-emerald-500">
                    Published
                </p>
                <p class="mt-1 text-2xl font-extrabold text-emerald-700">
                    {{ $testimonials->where('is_active', true)->count() }}
                </p>
            </div>

            <div class="rounded-2xl bg-yellow-50 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-[var(--color-brand-gold)]">
                    Featured
                </p>
                <p class="mt-1 text-2xl font-extrabold text-[var(--color-brand-gold)]">
                    {{ $testimonials->where('is_featured', true)->count() }}
                </p>
            </div>
        </div>
    </div>
</x-admin.table-card>