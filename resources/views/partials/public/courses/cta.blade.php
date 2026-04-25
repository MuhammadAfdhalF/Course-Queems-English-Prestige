<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 pb-16 lg:px-8 lg:pb-20">
        <div class="reveal mx-auto max-w-4xl rounded-[32px] bg-[#EEF3FF] px-6 py-16 text-center lg:px-12">
            <h2 class="reveal reveal-delay-1 text-4xl font-bold leading-tight text-slate-900 md:text-5xl">
                Ready to choose your <br> next English program?
            </h2>

            <p class="reveal reveal-delay-2 mx-auto mt-5 max-w-2xl text-base leading-8 text-slate-500">
                Explore premium courses tailored for academic, professional, and personal growth.
            </p>

            <div class="reveal reveal-delay-3 mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="{{ route('free-test') }}">
                    <x-ui.button variant="gold" class="px-8 py-4 text-base">
                        Start Free Test
                    </x-ui.button>
                </a>

                <a href="{{ route('contact') }}">
                    <x-ui.button variant="outline" class="px-8 py-4 text-base">
                        Contact Us
                    </x-ui.button>
                </a>
            </div>
        </div>
    </div>
</section>