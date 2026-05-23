<x-admin.page-toolbar
    :back-url="route('admin.cms.home.index')"
    back-label="Back to CMS">
    <x-slot:actions>
        <div class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-black text-slate-600 shadow-sm">
            {{ $totalTestimonials }} Testimonials
        </div>
    </x-slot:actions>
</x-admin.page-toolbar>

<x-admin.table-card class="overflow-hidden">
    <div class="grid gap-0 xl:grid-cols-[1fr_auto]">
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-[var(--color-brand-blue)] to-slate-800 p-7 text-white">
            <div class="pointer-events-none absolute -right-16 -top-16 h-44 w-44 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-20 h-44 w-44 rounded-full bg-yellow-400/20 blur-3xl"></div>

            <div class="relative">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-white/50">
                    Homepage Testimonials
                </p>

                <h2 class="mt-3 text-3xl font-black leading-tight">
                    Testimonial Display Control
                </h2>

                <p class="mt-3 max-w-3xl text-sm font-semibold leading-7 text-white/70">
                    Choose which student testimonials should appear on the homepage. Showing a testimonial on home automatically marks it as active and featured.
                </p>
            </div>
        </div>

        <div class="grid gap-3 bg-white p-6 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">
                    Total
                </p>
                <p class="mt-2 text-3xl font-black text-slate-900">
                    {{ $totalTestimonials }}
                </p>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                <p class="text-[11px] font-black uppercase tracking-[0.14em] text-emerald-500">
                    On Home
                </p>
                <p class="mt-2 text-3xl font-black text-emerald-700">
                    {{ $visibleTestimonials }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">
                    Hidden
                </p>
                <p class="mt-2 text-3xl font-black text-slate-700">
                    {{ $hiddenTestimonials }}
                </p>
            </div>

            <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3">
                <p class="text-[11px] font-black uppercase tracking-[0.14em] text-blue-500">
                    Course
                </p>
                <p class="mt-2 text-3xl font-black text-blue-700">
                    {{ $courseTestimonials }}
                </p>
            </div>

            <div class="rounded-2xl border border-purple-100 bg-purple-50 px-4 py-3">
                <p class="text-[11px] font-black uppercase tracking-[0.14em] text-purple-500">
                    Company
                </p>
                <p class="mt-2 text-3xl font-black text-purple-700">
                    {{ $companyTestimonials }}
                </p>
            </div>
        </div>
    </div>
</x-admin.table-card>