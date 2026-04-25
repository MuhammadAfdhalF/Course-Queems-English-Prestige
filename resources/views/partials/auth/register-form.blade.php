@extends('layouts.auth')

@section('content')
<section class="min-h-screen bg-slate-100 px-4 py-8 lg:px-6 lg:py-10">
    <div class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-7xl items-center justify-center">
        <div class="grid w-full max-w-6xl overflow-hidden rounded-[32px] border border-slate-300 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.08)] lg:grid-cols-[0.88fr_1.12fr]">
            {{-- LEFT PANEL --}}
            <div
                class="relative overflow-hidden px-8 py-10 text-white lg:px-10 lg:py-12"
                style="
                    background-image:
                        linear-gradient(rgba(8, 18, 96, 0.88), rgba(5, 15, 85, 0.95)),
                        url('{{ asset('images/background-login.jpeg') }}');
                    background-size: cover;
                    background-position: center;
                ">
                <div class="relative z-10 flex h-full flex-col justify-between">
                    <div>
                        <div class="reveal flex items-center gap-3">
                            <div class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-full bg-white/10 ring-2 ring-white/20 backdrop-blur-sm">
                                <img
                                    src="{{ asset('images/logo-queens-english-blue.png') }}"
                                    alt="Queens English Prestige Logo"
                                    class="h-full w-full object-cover"
                                    onerror="this.style.display='none';">
                            </div>

                            <div>
                                <p class="text-lg font-bold leading-tight text-white lg:text-[22px]">
                                    Queens English Prestige
                                </p>
                            </div>
                        </div>

                        <div class="mt-14">
                            <h1 class="reveal reveal-delay-1 text-4xl font-extrabold leading-[1.05] tracking-tight lg:text-[58px]">
                                Start Your
                                <span class="block text-[var(--color-brand-gold)]">Journey</span>
                            </h1>

                            <p class="reveal reveal-delay-2 mt-6 max-w-sm text-base leading-7 text-slate-100/95 lg:text-lg">
                                Create your account and unlock access to courses, progress tracking,
                                and certificates.
                            </p>
                        </div>

                        <div class="mt-10 space-y-5">
                            <div class="reveal reveal-delay-2 flex items-start gap-4">
                                <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[var(--color-brand-gold)] text-white shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="pt-1 text-base font-medium text-white lg:text-lg">
                                    Access premium learning modules
                                </p>
                            </div>

                            <div class="reveal reveal-delay-3 flex items-start gap-4">
                                <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[var(--color-brand-gold)] text-white shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="pt-1 text-base font-medium text-white lg:text-lg">
                                    Track progress and complete final exams
                                </p>
                            </div>

                            <div class="reveal reveal-delay-4 flex items-start gap-4">
                                <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[var(--color-brand-gold)] text-white shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="pt-1 text-base font-medium text-white lg:text-lg">
                                    Get certificates upon completion
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT PANEL --}}
            <div class="bg-slate-50 px-6 py-8 lg:px-10 lg:py-10">
                <div class="mx-auto w-full max-w-2xl rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm lg:p-10">
                    <div class="text-center">
                        <p class="reveal text-[11px] font-bold uppercase tracking-[0.24em] text-[var(--color-brand-gold)]">
                            Join Us
                        </p>

                        <h2 class="reveal reveal-delay-1 mt-3 text-3xl font-extrabold text-slate-900 lg:text-[40px]">
                            Create Account
                        </h2>
                    </div>

                    <form action="#" method="POST" class="mt-8 space-y-7">
                        @csrf

                        {{-- ACCOUNT INFORMATION --}}
                        <div class="reveal reveal-delay-1">
                            <h3 class="text-sm font-bold uppercase tracking-[0.12em] text-slate-900">
                                Account Information
                            </h3>

                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        Full Name <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19a4 4 0 00-8 0m8 0H5m10 0h4m-4 0a4 4 0 10-8 0m8 0a4 4 0 00-8 0M12 12a4 4 0 100-8 4 4 0 000 8z" />
                                            </svg>
                                        </span>
                                        <input
                                            type="text"
                                            name="name"
                                            placeholder="Full Name"
                                            class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        Email Address <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-18 8h18V8H3v8z" />
                                            </svg>
                                        </span>
                                        <input
                                            type="email"
                                            name="email"
                                            placeholder="Email Address"
                                            class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        Phone Number <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="grid gap-3 sm:grid-cols-[110px_1fr]">
                                        <input
                                            type="text"
                                            name="country_code"
                                            value="+62"
                                            class="focus-brand h-13 rounded-xl border border-slate-300 bg-slate-50 px-4 text-sm font-medium text-slate-700">

                                        <div class="relative">
                                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a2 2 0 011.9 1.37l1.07 3.21a2 2 0 01-.45 2.11l-1.27 1.27a16 16 0 006.36 6.36l1.27-1.27a2 2 0 012.11-.45l3.21 1.07A2 2 0 0121 18.72V22a2 2 0 01-2 2h-1C9.716 24 0 14.284 0 2V1a2 2 0 012-2h1z" />
                                                </svg>
                                            </span>
                                            <input
                                                type="text"
                                                name="phone"
                                                placeholder="Phone Number"
                                                class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">
                                            Password <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V9a4 4 0 10-8 0v2h8z" />
                                                </svg>
                                            </span>
                                            <input
                                                type="password"
                                                name="password"
                                                placeholder="Password"
                                                class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">
                                            Confirm Password <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V9a4 4 0 10-8 0v2h8z" />
                                                </svg>
                                            </span>
                                            <input
                                                type="password"
                                                name="password_confirmation"
                                                placeholder="Confirm Password"
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
                                        <label class="mb-2 block text-sm font-medium text-slate-700">
                                            Place of Birth <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 12.414M6.343 7.343a8 8 0 1111.314 11.314A8 8 0 016.343 7.343z" />
                                                </svg>
                                            </span>
                                            <input
                                                type="text"
                                                name="place_of_birth"
                                                placeholder="Place of Birth"
                                                class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">
                                            Date of Birth <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                            <input
                                                type="date"
                                                name="date_of_birth"
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
                                            <input type="radio" name="gender" value="male" class="border-slate-300 text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">
                                            <span>Male</span>
                                        </label>

                                        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                            <input type="radio" name="gender" value="female" class="border-slate-300 text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">
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
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        Full Address <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 12.414M6.343 7.343a8 8 0 1111.314 11.314A8 8 0 016.343 7.343z" />
                                            </svg>
                                        </span>
                                        <input
                                            type="text"
                                            name="address"
                                            placeholder="Full Address"
                                            class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">
                                            Occupation
                                        </label>
                                        <div class="relative">
                                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0112 20.055a12.083 12.083 0 01-6.16-9.477L12 14z" />
                                                </svg>
                                            </span>
                                            <input
                                                type="text"
                                                name="occupation"
                                                placeholder="Occupation"
                                                class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">
                                            Institution
                                        </label>
                                        <div class="relative">
                                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                            <input
                                                type="text"
                                                name="institution"
                                                placeholder="Institution"
                                                class="focus-brand h-13 w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
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
                                            type="text"
                                            name="instagram"
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
    </div>
</section>
@endsection