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
                <x-public.test-card
                    title="Grammar"
                    description="Test your understanding of tenses, structures, and common syntax..."
                    :href="route('free-test.show')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8M8 12h5m-5 5h8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3h12a2 2 0 012 2v14l-4-2-4 2-4-2-4 2V5a2 2 0 012-2z" />
                        </svg>
                    </x-slot:icon>
                </x-public.test-card>
            </div>

            <div class="reveal reveal-delay-1">
                <x-public.test-card
                    title="Reading"
                    description="Evaluate comprehension, speed, and vocabulary in context."
                    href="#">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v11.494m-5.747-8.62h11.494" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 5.25h15A2.25 2.25 0 0121.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9A2.25 2.25 0 014.5 5.25z" />
                        </svg>
                    </x-slot:icon>
                </x-public.test-card>
            </div>

            <div class="reveal reveal-delay-2">
                <x-public.test-card
                    title="Listening"
                    description="Assess your ability to understand native speakers and nuance."
                    href="#">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19V6l12-2v13" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm12-2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </x-slot:icon>
                </x-public.test-card>
            </div>

            <div class="reveal reveal-delay-3">
                <x-public.test-card
                    title="Vocabulary"
                    description="Check your lexical range and academic word knowledge."
                    href="#">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 8h10M7 12h6m-6 4h8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                    </x-slot:icon>
                </x-public.test-card>
            </div>
        </div>
    </div>
</section>