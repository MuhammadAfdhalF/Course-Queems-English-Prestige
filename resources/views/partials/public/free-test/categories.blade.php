<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="reveal text-3xl font-bold text-slate-900 md:text-4xl">
                    Test Categories
                </h2>
                <p class="reveal reveal-delay-1 mt-2 text-sm text-slate-500">
                    Pick a category to begin your assessment
                </p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="reveal">
                <a href="{{ route('free-test.show') }}" class="group motion-card block rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm transition duration-300 hover:shadow-md">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-[var(--color-brand-blue)] transition-all duration-300 group-hover:bg-blue-100 group-hover:text-[var(--color-brand-blue)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8M8 12h5m-5 5h8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3h12a2 2 0 012 2v14l-4-2-4 2-4-2-4 2V5a2 2 0 012-2z" />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-xl font-bold text-slate-900">Grammar</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Test your understanding of tenses, structures, and common syntax...
                    </p>

                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-brand-blue)]">
                        <span>Start Now</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="motion-link-arrow h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </a>
            </div>

            <div class="reveal reveal-delay-1">
                <a href="#" class="group motion-card block rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm transition duration-300 hover:shadow-md">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-[var(--color-brand-blue)] transition-all duration-300 group-hover:bg-blue-100 group-hover:text-[var(--color-brand-blue)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v11.494m-5.747-8.62h11.494" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 5.25h15A2.25 2.25 0 0121.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9A2.25 2.25 0 014.5 5.25z" />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-xl font-bold text-slate-900">Reading</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Evaluate comprehension, speed, and vocabulary in context.
                    </p>

                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-brand-blue)]">
                        <span>Start Now</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="motion-link-arrow h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </a>
            </div>

            <div class="reveal reveal-delay-2">
                <a href="#" class="group motion-card block rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm transition duration-300 hover:shadow-md">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-[var(--color-brand-blue)] transition-all duration-300 group-hover:bg-blue-100 group-hover:text-[var(--color-brand-blue)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19V6l12-2v13" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm12-2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-xl font-bold text-slate-900">Listening</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Assess your ability to understand native speakers and nuance.
                    </p>

                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-brand-blue)]">
                        <span>Start Now</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="motion-link-arrow h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </a>
            </div>

            <div class="reveal reveal-delay-3">
                <a href="#" class="group motion-card block rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm transition duration-300 hover:shadow-md">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-[var(--color-brand-blue)] transition-all duration-300 group-hover:bg-blue-100 group-hover:text-[var(--color-brand-blue)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 8h10M7 12h6m-6 4h8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-xl font-bold text-slate-900">Vocabulary</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Check your lexical range and academic word knowledge.
                    </p>

                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-brand-blue)]">
                        <span>Start Now</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="motion-link-arrow h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>