<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 md:text-4xl">
                    Test Categories
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Pick a category to begin your assessment
                </p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <x-public.test-category-card
                title="Grammar"
                duration="10 Minutes"
                questions="10 Questions"
                description="Test your understanding of tenses, sentence structure, and common grammar patterns."
                :href="route('free-test.show')">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h5M8 12h8M8 17h4" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 7l-2 2 2 2M17 17l-2-2 2-2" />
                    </svg>
                </x-slot:icon>
            </x-public.test-category-card>

            <x-public.test-category-card
                title="Reading"
                duration="15 Minutes"
                questions="12 Questions"
                description="Evaluate comprehension, reading speed, and vocabulary in context."
                href="#">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.5A2.5 2.5 0 016.5 4H10v16H6.5A2.5 2.5 0 014 17.5v-11zM20 6.5A2.5 2.5 0 0017.5 4H14v16h3.5a2.5 2.5 0 002.5-2.5v-11z" />
                    </svg>
                </x-slot:icon>
            </x-public.test-category-card>

            <x-public.test-category-card
                title="Listening"
                duration="15 Minutes"
                questions="10 Questions"
                description="Assess your ability to understand spoken English, meaning, and nuance."
                href="#">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 18V6a4 4 0 10-8 0v6a4 4 0 008 0zm0 0a4 4 0 008 0V6a4 4 0 10-8 0" />
                    </svg>
                </x-slot:icon>
            </x-public.test-category-card>

            <x-public.test-category-card
                title="Vocabulary"
                duration="15 Minutes"
                questions="12 Questions"
                description="Check your lexical range, word choice, and academic vocabulary knowledge."
                href="#">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.5A2.5 2.5 0 016.5 4H10v16H6.5A2.5 2.5 0 014 17.5v-11zM20 6.5A2.5 2.5 0 0017.5 4H14v16h3.5a2.5 2.5 0 002.5-2.5v-11z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 8h.01M16 8h.01" />
                    </svg>
                </x-slot:icon>
            </x-public.test-category-card>
        </div>
    </div>
</section>