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
                Password Recovery
            </p>

            <h2 class="mt-3 text-4xl font-black tracking-tight text-slate-900">
                Forgot Password?
            </h2>

            <p class="mt-3 text-sm font-semibold leading-7 text-slate-500">
                Enter the email address associated with your account. We will send you a password reset link.
            </p>
        </div>

        <form class="mt-8" action="{{ route('password.email') }}" method="POST">
            @csrf

            @if (session('status'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('status') }}
            </div>
            @endif

            @if (session('error'))
            <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                {{ session('error') }}
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 11-8 0 4 4 0 018 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="name@company.com"
                            required
                            autofocus
                            class="focus-brand h-14 w-full rounded-2xl border border-slate-200 bg-white pl-12 pr-4 text-sm font-medium text-slate-900 placeholder:text-slate-400">
                    </div>
                </div>

                <button
                    type="submit"
                    class="motion-button inline-flex h-14 w-full items-center justify-center gap-2 rounded-2xl bg-[var(--color-brand-blue)] text-base font-bold text-white shadow-[0_12px_24px_rgba(8,13,77,0.22)] hover:bg-[#0A1166]">
                    Send Reset Link
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-sm font-semibold text-slate-500">
            Remember your password?
            <a href="{{ route('login') }}" class="font-bold text-[#2457E6] hover:underline">
                Back to Sign In
            </a>
        </p>
    </div>
</div>
