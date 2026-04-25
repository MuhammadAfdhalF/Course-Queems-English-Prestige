<section class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="reveal text-2xl font-bold text-slate-900 md:text-3xl">
                    Our <span class="text-[var(--color-brand-gold)]">Programs</span>
                </h2>
                <p class="reveal reveal-delay-1 mt-3 text-base text-slate-600">
                    Choose the program that matches your level and learning goals.
                </p>
            </div>

            <a href="#" class="reveal reveal-delay-2 inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-brand-blue)] hover:opacity-80">
                <span>View All Programs</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="motion-link-arrow h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="reveal">
                <x-ui.course-card
                    title="Executive Communication"
                    level="Advanced"
                    mode="Online"
                    price="Rp. 180.000"
                    image="https://placehold.co/600x400/e7d8c7/1e293b?text=Executive+Communication" />
            </div>

            <div class="reveal reveal-delay-1">
                <x-ui.course-card
                    title="Academic Writing Mastery"
                    level="Intermediate"
                    mode="Online"
                    price="Rp. 180.000"
                    image="https://placehold.co/600x400/cfd8cf/1e293b?text=Academic+Writing" />
            </div>

            <div class="reveal reveal-delay-2">
                <x-ui.course-card
                    title="IELTS Masterclass 8.0+"
                    level="Beginner"
                    mode="Online"
                    price="Rp. 100.000"
                    image="https://placehold.co/600x400/f4e7d3/1e293b?text=IELTS+Masterclass" />
            </div>

            <div class="reveal reveal-delay-3">
                <x-ui.course-card
                    title="Grammar & Speaking Fundamentals"
                    level="Beginner"
                    mode="Offline"
                    price="Rp. 100.000"
                    image="https://placehold.co/600x400/f8edcf/1e293b?text=Grammar+%26+Speaking" />
            </div>
        </div>
    </div>
</section>