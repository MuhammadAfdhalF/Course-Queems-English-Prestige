<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-12 lg:px-8">
        @if (! empty($courseItems))
        <x-public.course-grid :courses="$courseItems" />
        @else
        <div class="reveal rounded-[28px] border border-slate-200 bg-[#F7FAFD] px-6 py-16 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white text-[var(--color-brand-blue)] shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v11.494m-5.747-8.62h11.494" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 5.25h15A2.25 2.25 0 0121.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9A2.25 2.25 0 014.5 5.25z" />
                </svg>
            </div>

            <h2 class="mt-5 text-2xl font-bold text-slate-900">
                No courses found.
            </h2>

            <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-500">
                Please try another filter or contact us for more information.
            </p>

            <div class="mt-7">
                <a href="{{ route('contact') }}">
                    <x-ui.button class="px-6 py-3">
                        Contact Us
                    </x-ui.button>
                </a>
            </div>
        </div>
        @endif
    </div>
</section>