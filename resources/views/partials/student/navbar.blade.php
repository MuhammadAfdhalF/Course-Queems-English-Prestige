@php
    $student = auth()->user();
    $studentName = $student?->name ?? 'Student';
    $studentInitials = collect(explode(' ', trim($studentName)))
        ->filter()
        ->take(2)
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->implode('');

    $studentInitials = $studentInitials ?: 'S';
@endphp

<header x-data="{ open: false, profileOpen: false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="flex items-center justify-between py-4">
            <a href="{{ route('student.dashboard') }}" class="flex min-w-0 items-center gap-3">
                <img
                    src="{{ asset('images/logo-queens-english.png') }}"
                    alt="Queens English Prestige Logo"
                    class="h-12 w-auto shrink-0 object-contain">

                <div class="min-w-0">
                    <p class="truncate text-xl font-bold leading-none text-slate-900">
                        Queens English Prestige
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Student Portal
                    </p>
                </div>
            </a>

            <div class="hidden items-center gap-4 lg:flex">
                <a
                    href="{{ route('home') }}"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50 hover:text-[var(--color-brand-blue)]">
                    Website
                </a>

                <div class="relative">
                    <button
                        @click="profileOpen = !profileOpen"
                        type="button"
                        class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 transition hover:shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-sm font-extrabold uppercase text-amber-700">
                            {{ $studentInitials }}
                        </div>

                        <div class="text-right">
                            <p class="max-w-[180px] truncate text-sm font-extrabold text-slate-900">
                                {{ $studentName }}
                            </p>

                            <p class="text-xs font-semibold text-slate-500">
                                Student
                            </p>
                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        x-show="profileOpen"
                        @click.outside="profileOpen = false"
                        x-transition
                        class="absolute right-0 z-50 mt-2 w-56 rounded-2xl border border-slate-200 bg-white p-2 shadow-lg">
                        <a
                            href="{{ route('student.profile') }}"
                            class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Profile
                        </a>

                        <a
                            href="{{ route('student.profile') }}"
                            class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Settings
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button
                                type="submit"
                                class="block w-full rounded-xl px-4 py-3 text-left text-sm font-semibold text-rose-600 transition hover:bg-rose-50">
                                Logout
                            </button>
                        </form>
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
                <a
                    href="{{ route('student.dashboard') }}"
                    class="pb-3 text-sm font-medium transition {{ request()->routeIs('student.dashboard') ? 'border-b-2 border-[var(--color-brand-gold)] font-semibold text-[var(--color-brand-blue)]' : 'text-slate-600 hover:text-[var(--color-brand-blue)]' }}">
                    Home
                </a>

                <a
                    href="{{ route('student.my-courses') }}"
                    class="pb-3 text-sm font-medium transition {{ request()->routeIs('student.my-courses') || request()->routeIs('student.learning-path') || request()->routeIs('student.module-material') || request()->routeIs('student.module-practice') || request()->routeIs('student.module-practice-result') || request()->routeIs('student.final-exam') || request()->routeIs('student.final-exam-result') || request()->routeIs('student.certificates.*') ? 'border-b-2 border-[var(--color-brand-gold)] font-semibold text-[var(--color-brand-blue)]' : 'text-slate-600 hover:text-[var(--color-brand-blue)]' }}">
                    My Course
                </a>

                <a
                    href="{{ route('student.all-courses') }}"
                    class="pb-3 text-sm font-medium transition {{ request()->routeIs('student.all-courses') ? 'border-b-2 border-[var(--color-brand-gold)] font-semibold text-[var(--color-brand-blue)]' : 'text-slate-600 hover:text-[var(--color-brand-blue)]' }}">
                    All Course
                </a>

                <a
                    href="{{ route('student.testimoni') }}"
                    class="pb-3 text-sm font-medium transition {{ request()->routeIs('student.testimoni') ? 'border-b-2 border-[var(--color-brand-gold)] font-semibold text-[var(--color-brand-blue)]' : 'text-slate-600 hover:text-[var(--color-brand-blue)]' }}">
                    Testimoni
                </a>

                <a
                    href="{{ route('student.profile') }}"
                    class="pb-3 text-sm font-medium transition {{ request()->routeIs('student.profile') ? 'border-b-2 border-[var(--color-brand-gold)] font-semibold text-[var(--color-brand-blue)]' : 'text-slate-600 hover:text-[var(--color-brand-blue)]' }}">
                    Profile
                </a>
            </nav>
        </div>
    </div>

    <div x-show="open" x-transition class="border-t border-slate-200 bg-white lg:hidden">
        <div class="space-y-4 px-4 py-4">
            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 px-3 py-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-sm font-extrabold uppercase text-amber-700">
                    {{ $studentInitials }}
                </div>

                <div class="min-w-0">
                    <p class="truncate text-sm font-extrabold text-slate-900">
                        {{ $studentName }}
                    </p>

                    <p class="text-xs font-semibold text-slate-500">
                        Student
                    </p>
                </div>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('student.dashboard') }}" class="block rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('student.dashboard') ? 'bg-blue-50 font-semibold text-[var(--color-brand-blue)]' : 'text-slate-700 hover:bg-slate-50' }}">
                    Home
                </a>

                <a href="{{ route('student.my-courses') }}" class="block rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('student.my-courses') || request()->routeIs('student.learning-path') || request()->routeIs('student.module-material') || request()->routeIs('student.module-practice') || request()->routeIs('student.module-practice-result') || request()->routeIs('student.final-exam') || request()->routeIs('student.final-exam-result') || request()->routeIs('student.certificates.*') ? 'bg-blue-50 font-semibold text-[var(--color-brand-blue)]' : 'text-slate-700 hover:bg-slate-50' }}">
                    My Course
                </a>

                <a href="{{ route('student.all-courses') }}" class="block rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('student.all-courses') ? 'bg-blue-50 font-semibold text-[var(--color-brand-blue)]' : 'text-slate-700 hover:bg-slate-50' }}">
                    All Course
                </a>

                <a href="{{ route('student.testimoni') }}" class="block rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('student.testimoni') ? 'bg-blue-50 font-semibold text-[var(--color-brand-blue)]' : 'text-slate-700 hover:bg-slate-50' }}">
                    Testimoni
                </a>

                <a href="{{ route('student.profile') }}" class="block rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('student.profile') ? 'bg-blue-50 font-semibold text-[var(--color-brand-blue)]' : 'text-slate-700 hover:bg-slate-50' }}">
                    Profile
                </a>

                <a href="{{ route('student.profile') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Settings
                </a>

                <a href="{{ route('home') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Back to Website
                </a>

                <form action="{{ route('logout') }}" method="POST" class="pt-2">
                    @csrf

                    <button
                        type="submit"
                        class="block w-full rounded-xl bg-rose-50 px-4 py-3 text-left text-sm font-bold text-rose-600 transition hover:bg-rose-100">
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </div>
</header>