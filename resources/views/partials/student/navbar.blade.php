<header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="flex items-center justify-between py-4">
            <div class="flex items-center gap-3">
                <img
                    src="{{ asset('images/logo-queens-english.png') }}"
                    alt="Queens English Prestige Logo"
                    class="h-12 w-auto object-contain">
                <div>
                    <p class="text-xl font-bold leading-none text-slate-900">Queens English Prestige</p>
                    <p class="mt-1 text-sm text-slate-500">Student Portal</p>
                </div>
            </div>

            <div class="hidden items-center gap-4 lg:flex">
                <button class="rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                    </svg>
                </button>

                <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-sm font-semibold text-amber-700">
                        AJ
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-900">Alex Johnson</p>
                        <p class="text-xs text-slate-500">Premium Student</p>
                    </div>
                </div>
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

        <div class="hidden border-t border-slate-200 lg:block">
            <nav class="flex items-center gap-8 py-4">
                <a href="{{ route('student.dashboard') }}" class="border-b-2 border-[var(--color-brand-blue)] pb-3 text-sm font-semibold text-[var(--color-brand-blue)]">Home</a>
                <a href="#" class="pb-3 text-sm font-medium text-slate-600 hover:text-[var(--color-brand-blue)]">My Course</a>
                <a href="#" class="pb-3 text-sm font-medium text-slate-600 hover:text-[var(--color-brand-blue)]">All Course</a>
                <a href="#" class="pb-3 text-sm font-medium text-slate-600 hover:text-[var(--color-brand-blue)]">Testimoni</a>
                <a href="#" class="pb-3 text-sm font-medium text-slate-600 hover:text-[var(--color-brand-blue)]">Profile</a>
                <a href="{{ route('home') }}" class="pb-3 text-sm font-medium text-slate-600 hover:text-[var(--color-brand-blue)]">Back to Website</a>
            </nav>
        </div>
    </div>

    <div x-show="open" x-transition class="border-t border-slate-200 bg-white lg:hidden">
        <div class="space-y-4 px-4 py-4">
            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 px-3 py-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-sm font-semibold text-amber-700">
                    AJ
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-900">Alex Johnson</p>
                    <p class="text-xs text-slate-500">Premium Student</p>
                </div>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('student.dashboard') }}" class="block rounded-xl bg-blue-50 px-4 py-3 text-sm font-semibold text-[var(--color-brand-blue)]">Home</a>
                <a href="#" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">My Course</a>
                <a href="#" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">All Course</a>
                <a href="#" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Testimoni</a>
                <a href="#" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Profile</a>
                <a href="{{ route('home') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Back to Website</a>
            </nav>
        </div>
    </div>
</header>