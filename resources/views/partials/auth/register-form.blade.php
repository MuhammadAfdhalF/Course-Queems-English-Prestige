<div class="relative hidden overflow-hidden lg:block">
    <img
        src="{{ asset('images/background-login.jpeg') }}"
        alt="Queens English Prestige Register Background"
        class="absolute inset-0 h-full w-full object-cover"
        onerror="this.style.display='none';">

    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(8,13,77,0.82)_0%,rgba(6,10,52,0.9)_100%)]"></div>

    <div class="absolute inset-0 opacity-70">
        <div class="absolute -right-20 top-6 h-[300px] w-[300px] rounded-full border border-[#d4a017]/35"></div>
        <div class="absolute -right-8 top-24 h-[420px] w-[420px] rounded-full border border-[#d4a017]/30"></div>
        <div class="absolute -right-16 bottom-8 h-[520px] w-[520px] rounded-full border border-[#d4a017]/30"></div>
        <div class="absolute -right-28 bottom-[-20px] h-[640px] w-[640px] rounded-full border border-[#d4a017]/20"></div>
    </div>

    <div class="relative z-10 flex h-full items-center px-10 py-14 xl:px-16">
        <div class="max-w-[460px] text-white">
            <div class="flex items-center gap-3">
                <div class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-full bg-white/10 ring-1 ring-white/20 xl:h-16 xl:w-16">
                    <img
                        src="{{ asset('images/logo-queens-english-blue.png') }}"
                        alt="Queens English Prestige Logo"
                        class="h-full w-full object-cover"
                        onerror="this.style.display='none';">
                </div>

                <p class="text-[20px] font-bold leading-tight xl:text-[22px]">
                    Queens English Prestige
                </p>
            </div>

            <div class="mt-12">
                <h1 class="text-[52px] font-extrabold leading-[1.04] xl:text-[60px]">
                    Start Your <span class="text-[var(--color-brand-gold)]">Journey</span>
                </h1>

                <p class="mt-7 max-w-[360px] text-[17px] leading-[1.5] text-white/90 xl:text-[18px]">
                    Create your student account and unlock access to courses, progress tracking, and certificates.
                </p>
            </div>

            <div class="mt-12 space-y-6">
                <div class="flex items-center gap-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#d4a017] text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <p class="text-[16px] font-medium text-white/95 xl:text-[17px]">
                        Premium learning modules
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#d4a017] text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <p class="text-[16px] font-medium text-white/95 xl:text-[17px]">
                        Progress tracking & final exam
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#d4a017] text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <p class="text-[16px] font-medium text-white/95 xl:text-[17px]">
                        Certificates upon completion
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex items-center justify-center px-5 py-8 sm:px-8 lg:px-10 lg:py-12">
    <div class="w-full max-w-[720px]">
        <div class="mx-auto w-full max-w-[680px] rounded-[28px] border border-slate-200 bg-white px-6 py-8 shadow-[0_6px_18px_rgba(15,23,42,0.08)] sm:px-8 lg:px-10 lg:py-10">
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
                <p class="reveal text-[11px] font-bold uppercase tracking-[0.24em] text-[var(--color-brand-gold)]">
                    Join Us
                </p>

                <h2 class="reveal reveal-delay-1 mt-3 text-[34px] font-extrabold tracking-tight text-slate-900 sm:text-[40px] lg:text-[42px]">
                    Create Account
                </h2>

                <p class="reveal reveal-delay-2 mt-3 text-[15px] leading-7 text-slate-700 sm:text-[16px]">
                    Fill in your student information to start learning with us.
                </p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="mt-8 space-y-7">
                @csrf

                @if ($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                    {{ $errors->first() }}
                </div>
                @endif

                {{-- ACCOUNT INFORMATION --}}
                <div class="reveal reveal-delay-1">
                    <h3 class="text-sm font-bold uppercase tracking-[0.12em] text-slate-900">
                        Account Information
                    </h3>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-slate-700">
                                Full Name <span class="text-rose-500">*</span>
                            </label>

                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19a4 4 0 00-8 0m8 0H5m10 0h4m-4 0a4 4 0 10-8 0m8 0a4 4 0 00-8 0M12 12a4 4 0 100-8 4 4 0 000 8z" />
                                    </svg>
                                </span>

                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Full Name"
                                    required
                                    class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                                Email Address <span class="text-rose-500">*</span>
                            </label>

                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-18 8h18V8H3v8z" />
                                    </svg>
                                </span>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Email Address"
                                    required
                                    class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="mb-2 block text-sm font-medium text-slate-700">
                                Phone Number <span class="text-rose-500">*</span>
                            </label>

                            <div class="grid gap-3 sm:grid-cols-[110px_1fr]">
                                <input
                                    type="text"
                                    name="country_code"
                                    value="{{ old('country_code', '+62') }}"
                                    required
                                    class="focus-brand h-13 rounded-xl border border-slate-300 bg-slate-50 px-4 text-sm font-medium text-slate-700">

                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a2 2 0 011.9 1.37l1.07 3.21a2 2 0 01-.45 2.11l-1.27 1.27a16 16 0 006.36 6.36l1.27-1.27a2 2 0 012.11-.45l3.21 1.07A2 2 0 0121 18.72V22a2 2 0 01-2 2h-1C9.716 24 0 14.284 0 2V1a2 2 0 012-2h1z" />
                                        </svg>
                                    </span>

                                    <input
                                        id="phone"
                                        type="text"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        placeholder="Phone Number"
                                        required
                                        class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">
                                    Password <span class="text-rose-500">*</span>
                                </label>

                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V9a4 4 0 10-8 0v2h8z" />
                                        </svg>
                                    </span>

                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        placeholder="Password"
                                        required
                                        class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                </div>
                            </div>

                            <div>
                                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">
                                    Confirm Password <span class="text-rose-500">*</span>
                                </label>

                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V9a4 4 0 10-8 0v2h8z" />
                                        </svg>
                                    </span>

                                    <input
                                        id="password_confirmation"
                                        type="password"
                                        name="password_confirmation"
                                        placeholder="Confirm Password"
                                        required
                                        class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                </div>
                            </div>
                        </div>

                        <p class="-mt-1 text-right text-xs text-slate-400">
                            Minimum 8 characters
                        </p>
                    </div>
                </div>

                {{-- PERSONAL DETAILS --}}
                <div class="reveal reveal-delay-2">
                    <h3 class="text-sm font-bold uppercase tracking-[0.12em] text-slate-900">
                        Personal Details
                    </h3>

                    <div class="mt-4 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="place_of_birth" class="mb-2 block text-sm font-medium text-slate-700">
                                    Place of Birth <span class="text-rose-500">*</span>
                                </label>

                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 12.414M6.343 7.343a8 8 0 1111.314 11.314A8 8 0 016.343 7.343z" />
                                        </svg>
                                    </span>

                                    <input
                                        id="place_of_birth"
                                        type="text"
                                        name="place_of_birth"
                                        value="{{ old('place_of_birth') }}"
                                        placeholder="Place of Birth"
                                        required
                                        class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                </div>
                            </div>

                            <div>
                                <label for="date_of_birth" class="mb-2 block text-sm font-medium text-slate-700">
                                    Date of Birth <span class="text-rose-500">*</span>
                                </label>

                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z" />
                                        </svg>
                                    </span>

                                    <input
                                        id="date_of_birth"
                                        type="date"
                                        name="date_of_birth"
                                        value="{{ old('date_of_birth') }}"
                                        required
                                        class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-3 block text-sm font-medium text-slate-700">
                                Gender <span class="text-rose-500">*</span>
                            </label>

                            <div class="flex flex-wrap items-center gap-6">
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                    <input
                                        type="radio"
                                        name="gender"
                                        value="male"
                                        required
                                        @checked(old('gender')==='male' )
                                        class="border-slate-300 text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">
                                    <span>Male</span>
                                </label>

                                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                    <input
                                        type="radio"
                                        name="gender"
                                        value="female"
                                        required
                                        @checked(old('gender')==='female' )
                                        class="border-slate-300 text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">
                                    <span>Female</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ADDITIONAL INFORMATION --}}
                <div class="reveal reveal-delay-3">
                    <h3 class="text-sm font-bold uppercase tracking-[0.12em] text-slate-900">
                        Additional Information
                    </h3>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="address" class="mb-2 block text-sm font-medium text-slate-700">
                                Full Address <span class="text-rose-500">*</span>
                            </label>

                            <div class="relative">
                                <span class="pointer-events-none absolute left-0 top-3 flex pl-4 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 12.414M6.343 7.343a8 8 0 1111.314 11.314A8 8 0 016.343 7.343z" />
                                    </svg>
                                </span>

                                <textarea
                                    id="address"
                                    name="address"
                                    rows="3"
                                    placeholder="Full Address"
                                    required
                                    class="focus-brand w-full resize-none rounded-xl border border-slate-300 bg-white py-3 pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="occupation" class="mb-2 block text-sm font-medium text-slate-700">
                                    Occupation
                                </label>

                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0112 20.055a12.083 12.083 0 01-6.16-9.477L12 14z" />
                                        </svg>
                                    </span>

                                    <input
                                        id="occupation"
                                        type="text"
                                        name="occupation"
                                        value="{{ old('occupation') }}"
                                        placeholder="Occupation"
                                        class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                </div>
                            </div>

                            <div>
                                <label for="institution" class="mb-2 block text-sm font-medium text-slate-700">
                                    Institution
                                </label>

                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z" />
                                        </svg>
                                    </span>

                                    <input
                                        id="institution"
                                        type="text"
                                        name="institution"
                                        value="{{ old('institution') }}"
                                        placeholder="Institution"
                                        class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="instagram" class="mb-2 block text-sm font-medium text-slate-700">
                                Instagram
                            </label>

                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <rect x="4" y="4" width="16" height="16" rx="4" stroke-width="1.8" />
                                        <circle cx="12" cy="12" r="3.2" stroke-width="1.8" />
                                        <circle cx="16.5" cy="7.5" r="0.8" fill="currentColor" stroke="none" />
                                    </svg>
                                </span>

                                <input
                                    id="instagram"
                                    type="text"
                                    name="instagram"
                                    value="{{ old('instagram') }}"
                                    placeholder="Instagram (username or link)"
                                    class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="reveal reveal-delay-4 pt-2">
                    <button
                        type="submit"
                        class="motion-button inline-flex h-14 w-full items-center justify-center rounded-xl bg-[var(--color-brand-blue)] text-base font-bold text-white shadow-md transition hover:opacity-95">
                        Sign Up
                    </button>

                    <p class="mt-4 text-center text-xs text-slate-500">
                        WhatsApp confirmation is required for enrollment approval.
                    </p>

                    <p class="mt-5 text-center text-sm text-slate-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-[var(--color-brand-blue)] hover:underline">
                            Sign in
                        </a>
                    </p>

                    <div class="mt-3 text-center">
                        <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-[var(--color-brand-blue)]">
                            Back to Home
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>