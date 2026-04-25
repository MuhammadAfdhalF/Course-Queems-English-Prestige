<section class="relative overflow-hidden border-b border-slate-200 bg-[#F7FAFD]">
    <div class="absolute inset-0">
        <img
            src="{{ asset('images/about-hero-bg.png') }}"
            alt="Contact Hero Background"
            class="h-full w-full object-cover opacity-100"
            onerror="this.style.display='none';">
    </div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 text-center lg:px-8 lg:py-20">
        <h1 class="mt-2 text-4xl font-bold leading-tight text-[var(--color-brand-blue)] md:text-5xl lg:text-6xl">
            Contact
            <span class="text-[var(--color-brand-gold)]">Us</span>
        </h1>

        <p class="mx-auto mt-5 max-w-3xl text-base leading-8 text-slate-500 md:text-lg">
            Embark on your journey to English mastery with expert guidance.
            We are here to support every step of your progress.
        </p>

        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a
                href="{{ route('free-test') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F4BE1A] px-6 py-3 text-sm font-bold text-slate-900 transition hover:opacity-90">
                Start Free Test
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M13 5l7 7-7 7" />
                </svg>
            </a>

            <a
                href="{{ route('courses') }}"
                class="inline-flex items-center justify-center rounded-xl border border-[#2457E6] px-6 py-3 text-sm font-bold text-[#2457E6] transition hover:bg-blue-50">
                Explore Courses
            </a>
        </div>
    </div>
</section>