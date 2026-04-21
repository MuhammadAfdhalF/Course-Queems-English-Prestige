<header x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img
                src="{{ asset('images/logo-queens-english.png') }}"
                alt="Queens English Prestige Logo"
                class="h-10 w-auto object-contain">
            <div>
                <p class="text-base font-bold leading-none text-slate-900">Queens English Prestige</p>
            </div>
        </a>

        <nav class="hidden items-center gap-8 lg:flex">
            <x-public.nav-link :href="route('home')" :active="request()->routeIs('home')">
                Home
            </x-public.nav-link>

            <x-public.nav-link :href="route('about')" :active="request()->routeIs('about')">
                About Us
            </x-public.nav-link>

            <x-public.nav-link :href="route('courses')" :active="request()->routeIs('courses')">
                Course
            </x-public.nav-link>

            <x-public.nav-link :href="route('free-test')" :active="request()->routeIs('free-test')">
                Free Test
            </x-public.nav-link>

            <x-public.nav-link :href="route('news')" :active="request()->routeIs('news')">
                News
            </x-public.nav-link>

            <x-public.nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                Contact
            </x-public.nav-link>
        </nav>

        <div class="hidden lg:block">
            <x-ui.button class="px-5 py-2.5">Get Started</x-ui.button>
        </div>

        <button
            @click="open = !open"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 p-2 text-slate-700 lg:hidden"
            type="button">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <div x-show="open" x-transition class="border-t border-slate-200 bg-white lg:hidden">
        <nav class="space-y-1 px-4 py-4">
            <a href="{{ route('home') }}" class="block rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('home') ? 'bg-blue-50 text-[var(--color-brand-blue)]' : 'text-slate-700 hover:bg-slate-50' }}">
                Home
            </a>

            <a href="{{ route('about') }}" class="block rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('about') ? 'bg-blue-50 text-[var(--color-brand-blue)]' : 'text-slate-700 hover:bg-slate-50' }}">
                About Us
            </a>

            <a href="{{ route('courses') }}" class="block rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('courses') ? 'bg-blue-50 text-[var(--color-brand-blue)]' : 'text-slate-700 hover:bg-slate-50' }}">
                Course
            </a>

            <a href="{{ route('free-test') }}" class="block rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('free-test') ? 'bg-blue-50 text-[var(--color-brand-blue)]' : 'text-slate-700 hover:bg-slate-50' }}">
                Free Test
            </a>

            <a href="{{ route('news') }}" class="block rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('news') ? 'bg-blue-50 text-[var(--color-brand-blue)]' : 'text-slate-700 hover:bg-slate-50' }}">
                News
            </a>

            <a href="{{ route('contact') }}" class="block rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('contact') ? 'bg-blue-50 text-[var(--color-brand-blue)]' : 'text-slate-700 hover:bg-slate-50' }}">
                Contact
            </a>

            <div class="pt-3">
                <x-ui.button class="w-full">Get Started</x-ui.button>
            </div>
        </nav>
    </div>
</header>