<header x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img
                src="{{ asset('images/logo-queens-english.png') }}"
                alt="Queens English Prestige Logo"
                class="h-10 w-auto object-contain" ><div>
            <p class="text-base font-bold leading-none text-slate-900">Queens English Prestige</p>
            <p class="text-xs text-slate-400">Premium English Course</p>
    </div>
    </a>

    <nav class="hidden items-center gap-8 lg:flex">
        <a href="{{ route('home') }}" class="text-sm font-medium text-slate-700 hover:text-[var(--color-brand-blue)]">Home</a>
        <a href="#" class="text-sm font-medium text-slate-700 hover:text-[var(--color-brand-blue)]">About Us</a>
        <a href="#" class="text-sm font-medium text-slate-700 hover:text-[var(--color-brand-blue)]">Course</a>
        <a href="#" class="text-sm font-medium text-slate-700 hover:text-[var(--color-brand-blue)]">Free Test</a>
        <a href="#" class="text-sm font-medium text-slate-700 hover:text-[var(--color-brand-blue)]">News</a>
        <a href="#" class="text-sm font-medium text-slate-700 hover:text-[var(--color-brand-blue)]">Contact</a>
    </nav>

    <div class="hidden lg:block">
        <x-ui.button>Get Started</x-ui.button>
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
            <a href="{{ route('home') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Home</a>
            <a href="#" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">About Us</a>
            <a href="#" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Course</a>
            <a href="#" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Free Test</a>
            <a href="#" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">News</a>
            <a href="#" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Contact</a>

            <div class="pt-3">
                <x-ui.button class="w-full">Get Started</x-ui.button>
            </div>
        </nav>
    </div>
</header>