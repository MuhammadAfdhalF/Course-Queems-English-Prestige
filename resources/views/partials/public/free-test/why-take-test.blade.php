<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="text-center">
            <h2 class="reveal text-3xl font-bold text-slate-900 md:text-4xl">
                Why Take the Test
            </h2>

            <div class="reveal reveal-delay-1 mx-auto mt-3 h-1 w-16 rounded-full bg-[var(--color-brand-gold)]"></div>
        </div>

        <div class="mx-auto mt-12 grid max-w-5xl gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="reveal">
                <x-public.test-benefit-card
                    title="Level Check"
                    description="Understand your current proficiency across all key skills accurately.">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h4v7H5zM10 5h4v14h-4zM15 9h4v10h-4z" />
                        </svg>
                    </x-slot:icon>
                </x-public.test-benefit-card>
            </div>

            <div class="reveal reveal-delay-1">
                <x-public.test-benefit-card
                    title="Course Matching"
                    description="Get tailored recommendations for courses that fit your specific level.">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="11" cy="11" r="7" stroke-width="1.8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 20l-4-4" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 11l2 2 4-4" />
                        </svg>
                    </x-slot:icon>
                </x-public.test-benefit-card>
            </div>

            <div class="reveal reveal-delay-2">
                <x-public.test-benefit-card
                    title="Strength ID"
                    description="Know exactly where you excel and which areas need more focus.">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                        </svg>
                    </x-slot:icon>
                </x-public.test-benefit-card>
            </div>

            <div class="reveal reveal-delay-3">
                <x-public.test-benefit-card
                    title="Efficiency"
                    description="Receive fast, data-driven results that respect your valuable time.">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 2L4 14h7l-1 8 9-12h-7l1-8z" />
                        </svg>
                    </x-slot:icon>
                </x-public.test-benefit-card>
            </div>
        </div>
    </div>
</section>