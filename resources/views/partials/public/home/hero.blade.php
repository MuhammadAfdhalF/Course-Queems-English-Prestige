<section class="relative overflow-hidden bg-white">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-14 lg:grid-cols-2 lg:px-8 lg:py-16">
        <div class="max-w-2xl">
            <div class="reveal inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">
                <span class="inline-block h-2 w-2 rounded-full bg-amber-500"></span>
                Premium Learning Program
            </div>

            <h1 class="reveal reveal-delay-1 mt-6 text-4xl font-extrabold leading-[1.05] text-slate-900 md:text-5xl lg:text-[72px]">
                Master <br>English for
                <span class="block text-[var(--color-brand-gold)]">Global Communication</span>
            </h1>

            <p class="reveal reveal-delay-2 mt-6 max-w-xl text-base leading-8 text-slate-600">
                Build real speaking, grammar, and test-ready skills with a clear learning path,
                practical exercises, and measurable progress so you can communicate confidently
                for study, work, and everyday global conversations.
            </p>

            <div class="reveal reveal-delay-3 mt-8 flex flex-col gap-3 sm:flex-row">
                <x-ui.button class="px-6 py-3">
                    Explore Courses
                </x-ui.button>

                <x-ui.button variant="outline" class="px-6 py-3">
                    Try Free Test
                </x-ui.button>
            </div>

            <div class="reveal reveal-delay-4 mt-5 flex items-start gap-2 text-sm text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 text-[var(--color-brand-blue)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11 5.176-.71 9-5.409 9-11 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <p>Payment confirmation via WhatsApp. Course access unlocks after approval.</p>
            </div>
        </div>

        <div class="reveal reveal-delay-2 relative">
            <div class="relative mx-auto max-w-xl">
                <div class="absolute -left-4 top-10 hidden h-16 w-16 rounded-full bg-blue-100 lg:block"></div>
                <div class="absolute right-6 top-0 hidden h-6 w-6 rounded-full bg-amber-400 lg:block"></div>
                <div class="absolute left-1/3 top-6 hidden h-3 w-3 rounded-full bg-slate-300 lg:block"></div>

                <div class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-6 ring-1 ring-slate-200">
                    <img
                        src="{{ asset('images/hero-queens.png') }}"
                        alt="Queens English Prestige Hero Illustration"
                        class="motion-image h-full w-full object-contain"
                        onerror="this.src='https://placehold.co/800x600/f8fafc/1e293b?text=Queens+English+Prestige';">
                </div>

                <div class="reveal reveal-delay-4 absolute bottom-8 left-0 hidden w-56 rounded-2xl border border-slate-200 bg-white p-4 shadow-lg lg:block">
                    <div class="flex items-center gap-3">
                        <div class="flex -space-x-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-white bg-blue-100 text-xs font-semibold text-blue-700">A</div>
                            <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-white bg-amber-100 text-xs font-semibold text-amber-700">J</div>
                            <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-white bg-emerald-100 text-xs font-semibold text-emerald-700">R</div>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-slate-900">1,200+ Students</p>
                            <p class="text-xs text-slate-500">Joined this month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>