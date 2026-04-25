<div class="flex items-center justify-center px-5 py-8 sm:px-8 lg:px-10 lg:py-12">
    <div class="w-full max-w-[620px]">
        <div class="mx-auto w-full max-w-[560px] border border-slate-200 bg-white px-6 py-8 shadow-[0_6px_18px_rgba(15,23,42,0.08)] sm:px-8 lg:px-10 lg:py-10">
            <div class="reveal flex items-center justify-center gap-4">
                <img
                    src="{{ asset('images/logo-queens-english.png') }}"
                    alt="Queens English Prestige Logo"
                    class="h-16 w-auto object-contain sm:h-20 lg:h-24"
                    onerror="this.style.display='none';">

                <div>
                    <p class="text-[18px] font-extrabold leading-tight text-slate-900 sm:text-[20px] lg:text-[21px]">
                        Queens<br>English Prestige
                    </p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <h2 class="reveal reveal-delay-1 text-[34px] font-extrabold tracking-tight text-slate-900 sm:text-[40px] lg:text-[42px]">
                    Sign In
                </h2>

                <p class="reveal reveal-delay-2 mt-3 text-[15px] leading-7 text-slate-700 sm:text-[16px]">
                    Use your email and password to continue learning
                </p>
            </div>

            <form class="mt-8" action="#" method="POST">
                <div class="reveal reveal-delay-2 space-y-5">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7l8.2 5.47a1.5 1.5 0 001.66 0L21 7" />
                                <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.8" />
                            </svg>
                        </span>

                        <input
                            type="email"
                            name="email"
                            placeholder="Email Address"
                            class="focus-brand h-12 w-full rounded-[10px] border border-slate-500 bg-white pl-14 pr-4 text-[15px] text-slate-900 placeholder:text-slate-400">
                    </div>

                    <div>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <rect x="5" y="11" width="14" height="10" rx="2" stroke-width="1.8" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 11V8a4 4 0 118 0v3" />
                                </svg>
                            </span>

                            <input
                                type="password"
                                name="password"
                                placeholder="Password"
                                class="focus-brand h-12 w-full rounded-[10px] border border-slate-500 bg-white pl-14 pr-14 text-[15px] text-slate-900 placeholder:text-slate-400">

                            <button
                                type="button"
                                class="motion-button absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" />
                                    <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-2 text-right">
                            <a href="#" class="text-[14px] font-medium text-[#2457E6] hover:underline">
                                forget password ?
                            </a>
                        </div>
                    </div>
                </div>

                <div class="reveal reveal-delay-3 mt-7">
                    <button
                        type="submit"
                        class="motion-button inline-flex h-12 w-full items-center justify-center rounded-[10px] bg-[#020b2c] text-[16px] font-bold text-white shadow-[0_6px_16px_rgba(2,11,44,0.18)] transition hover:opacity-95">
                        Sign In
                    </button>
                </div>

                <div class="reveal reveal-delay-4 mt-6 text-center">
                    <p class="text-[16px] text-slate-900">
                        Don’t have an account ?
                    </p>
                    <a href="{{ route('register') }}" class="mt-1 inline-block text-[16px] font-medium text-[var(--color-brand-gold)] underline decoration-[1.5px] underline-offset-2">
                        Create Account
                    </a>
                </div>
            </form>
        </div>

        <div class="reveal reveal-delay-4 mt-6 text-center">
            <a href="{{ route('home') }}" class="text-[15px] font-medium text-[#2457E6] underline underline-offset-2 hover:opacity-80">
                Back to Home
            </a>
        </div>
    </div>
</div>