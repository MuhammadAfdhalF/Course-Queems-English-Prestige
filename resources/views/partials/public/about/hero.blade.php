<section class="relative overflow-hidden border-b border-slate-200 bg-[#F7FAFD]">
    <div class="absolute inset-0">
        <img
            src="{{ asset('images/about-hero-bg.png') }}"
            alt="About Hero Background"
            class="h-full w-full object-cover opacity-100"
            onerror="this.style.display='none';">
    </div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 lg:px-8 lg:py-20">
        <div class="max-w-xl">
            <div class="reveal inline-flex items-center gap-2 rounded-md border border-amber-200 bg-white/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-[var(--color-brand-gold)]">
                <span>🏛</span>
                Our Story
            </div>

            <h1 class="reveal reveal-delay-1 mt-5 text-4xl font-bold leading-tight text-[var(--color-brand-blue)] md:text-5xl">
                About <span class="text-[var(--color-brand-gold)]">Us</span>
            </h1>

            <p class="reveal reveal-delay-2 mt-5 max-w-lg text-base leading-8 text-slate-600">
                “Premium English learning—online modules and offline sessions—built for
                real communication skills and measurable progress.”
            </p>

            <div class="reveal reveal-delay-3 mt-8 flex flex-col gap-3 sm:flex-row">
                <x-ui.button class="px-6 py-3">
                    Explore Courses
                </x-ui.button>

                <x-ui.button variant="outline" class="px-6 py-3">
                    Contact Us
                </x-ui.button>
            </div>
        </div>
    </div>
</section>