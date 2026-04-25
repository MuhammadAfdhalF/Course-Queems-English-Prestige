<section class="relative overflow-hidden border-b border-slate-200 bg-[#F7FAFD]">
    <div class="absolute inset-0">
        <img
            src="{{ asset('images/course-hero-bg.png') }}"
            alt="Free Test Hero Background"
            class="h-full w-full object-cover"
            onerror="this.style.display='none';">
    </div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 text-center lg:px-8 lg:py-20">
        <p class="reveal text-xs font-semibold uppercase tracking-[0.22em] text-[#2457E6]">
            100% Free Assessment
        </p>

        <h1 class="reveal reveal-delay-1 mt-4 text-4xl font-bold leading-tight text-[var(--color-brand-blue)] md:text-5xl">
            Free English <br>
            <span class="text-[var(--color-brand-gold)]">Test Hub</span>
        </h1>

        <p class="reveal reveal-delay-2 mx-auto mt-5 max-w-2xl text-base leading-8 text-slate-500">
            Discover your true proficiency level in 30 minutes. Get data-driven insights
            and find the perfect course for your professional goals.
        </p>

        <div class="reveal reveal-delay-3 mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a
                href="#"
                class="motion-button inline-flex items-center justify-center gap-2 rounded-xl bg-[#F4BE1A] px-6 py-3 text-sm font-bold text-slate-900 transition hover:opacity-90">
                Start Free Test
                <svg xmlns="http://www.w3.org/2000/svg" class="motion-link-arrow h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M13 5l7 7-7 7" />
                </svg>
            </a>

            <a
                href="{{ route('courses') }}"
                class="motion-button inline-flex items-center justify-center rounded-xl border border-[#2457E6] px-6 py-3 text-sm font-bold text-[#2457E6] transition hover:bg-blue-50">
                Explore Courses
            </a>
        </div>
    </div>
</section>