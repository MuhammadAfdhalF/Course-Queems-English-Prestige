<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Learning Area - Queens English Prestige' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

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

<body class="min-h-screen bg-slate-50 text-slate-900">
    <header x-data="{ profileOpen: false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 lg:px-8">
            <a href="{{ route('student.dashboard') }}" class="flex min-w-0 items-center gap-3">
                <img
                    src="{{ asset('images/logo-queens-english.png') }}"
                    alt="Queens English Prestige Logo"
                    class="h-9 w-auto shrink-0 object-contain"
                    onerror="this.style.display='none';">

                <div class="min-w-0">
                    <p class="truncate text-sm font-extrabold leading-tight text-[var(--color-brand-blue)]">
                        Queens English Prestige
                    </p>

                    <p class="mt-0.5 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">
                        Learning Area
                    </p>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <a
                    href="{{ route('student.dashboard') }}"
                    class="hidden rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50 hover:text-[var(--color-brand-blue)] md:inline-flex">
                    Dashboard
                </a>

                <a
                    href="{{ route('student.my-courses') }}"
                    class="hidden rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50 hover:text-[var(--color-brand-blue)] sm:inline-flex">
                    My Courses
                </a>

                <div class="relative">
                    <button
                        @click="profileOpen = !profileOpen"
                        type="button"
                        class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 transition hover:shadow-sm">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[var(--color-brand-blue)] text-xs font-extrabold uppercase text-white">
                            {{ $studentInitials }}
                        </div>

                        <div class="hidden text-right sm:block">
                            <p class="max-w-[160px] truncate text-xs font-extrabold text-slate-900">
                                {{ $studentName }}
                            </p>

                            <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                                Student
                            </p>
                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4 text-slate-400 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        </div>
    </header>

    <main class="px-4 py-6 lg:px-8 lg:py-8">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white py-5">
        <div class="mx-auto max-w-7xl px-4 text-center lg:px-8">
            <p class="text-xs font-medium text-slate-400">
                © 2003 Queens English Prestige. Focus mode for learning.
            </p>
        </div>
    </footer>
</body>

</html>