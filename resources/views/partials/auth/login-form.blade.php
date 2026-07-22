<div class="flex min-h-[680px] items-center justify-center px-5 py-10 sm:px-8 lg:px-10">
    <div class="w-full max-w-md">
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-3">
                <img
                    src="{{ asset('images/logo-queens-english.png') }}"
                    alt="Queens English Prestige Logo"
                    class="h-16 w-auto object-contain"
                    onerror="this.style.display='none';">

                <span class="text-left text-xl font-black leading-tight text-slate-900">
                    Queens<br>English Prestige
                </span>
            </a>

            <p class="mt-8 text-xs font-black uppercase tracking-[0.22em] text-[var(--color-brand-gold)]">
                Student Login
            </p>

            <h2 class="mt-3 text-4xl font-black tracking-tight text-slate-900">
                Sign In
            </h2>

            <p class="mt-3 text-sm font-semibold leading-7 text-slate-500">
                Use your email and password to continue learning.
            </p>
        </div>

        <form class="mt-8" action="{{ route('login.store') }}" method="POST">
            @csrf

            @if (session('success'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="space-y-5">
                <div>
                    <label for="email" class="mb-2 block text-sm font-bold text-slate-700">
                        Email Address
                    </label>

                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7l8.2 5.47a1.5 1.5 0 001.66 0L21 7" />
                                <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.8" />
                            </svg>
                        </span>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="admin@gmail.com"
                            required
                            class="focus-brand h-13 w-full rounded-2xl border border-slate-200 bg-white pl-12 pr-4 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400">
                    </div>
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between gap-4">
                        <label for="password" class="block text-sm font-bold text-slate-700">
                            Password
                        </label>

                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#2457E6] hover:underline">
                            Forgot password?
                        </a>
                    </div>

                    <div
                        x-data="{ show: false }"
                        class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="5" y="11" width="14" height="10" rx="2" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 11V8a4 4 0 118 0v3" />
                            </svg>
                        </span>

                        <input
                            id="password"
                            :type="show ? 'text' : 'password'"
                            name="password"
                            placeholder="Enter password"
                            required
                            class="focus-brand h-13 w-full rounded-2xl border border-slate-200 bg-white pl-12 pr-12 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400">

                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition hover:text-slate-600">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" />
                                <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                            </svg>

                            <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18M10.6 10.6A2.5 2.5 0 0012 14.5c.68 0 1.3-.27 1.75-.72M7.2 7.6C3.9 9.2 2 12 2 12s3.5 6 10 6c1.4 0 2.68-.28 3.82-.75M17.5 14.7C20.3 13.1 22 12 22 12s-3.5-6-10-6c-.9 0-1.75.06-2.53.2" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <button
                type="submit"
                class="motion-button mt-7 inline-flex h-13 w-full items-center justify-center rounded-2xl bg-[#020b2c] text-sm font-black text-white shadow-[0_12px_24px_rgba(2,11,44,0.22)] transition hover:-translate-y-0.5 hover:opacity-95">
                Sign In
            </button>

            <div class="mt-7 text-center">
                <p class="text-sm font-semibold text-slate-500">
                    Don’t have an account?
                </p>

                <a href="{{ route('register') }}" class="mt-1 inline-block text-sm font-black text-[var(--color-brand-gold)] underline underline-offset-4">
                    Create Account
                </a>
            </div>
        </form>

        <div class="mt-7 text-center">
            <a href="{{ route('home') }}" class="text-sm font-bold text-[#2457E6] hover:underline">
                Back to Home
            </a>
        </div>
    </div>
</div>