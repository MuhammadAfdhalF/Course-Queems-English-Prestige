<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Learning Area - Queens English Prestige' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
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

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('student.my-courses') }}"
                    class="hidden rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50 hover:text-[var(--color-brand-blue)] sm:inline-flex">
                    My Courses
                </a>

                <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[var(--color-brand-blue)] text-xs font-extrabold uppercase text-white">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'S', 0, 1)) }}
                    </div>

                    <div class="hidden text-right sm:block">
                        <p class="max-w-[160px] truncate text-xs font-extrabold text-slate-900">
                            {{ auth()->user()?->name ?? 'Student' }}
                        </p>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                            Student
                        </p>
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