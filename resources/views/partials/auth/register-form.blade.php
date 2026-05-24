<div class="flex items-center justify-center px-5 py-8 sm:px-8 lg:px-10">
    <div class="w-full max-w-3xl">
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-3">
                <img
                    src="{{ asset('images/logo-queens-english.png') }}"
                    alt="Queens English Prestige Logo"
                    class="h-14 w-auto object-contain"
                    onerror="this.style.display='none';">

                <span class="text-left text-lg font-black leading-tight text-slate-900">
                    Queens<br>English Prestige
                </span>
            </a>

            <p class="mt-7 text-xs font-black uppercase tracking-[0.22em] text-[var(--color-brand-gold)]">
                Join Us
            </p>

            <h2 class="mt-3 text-4xl font-black tracking-tight text-slate-900">
                Create Account
            </h2>

            <p class="mt-3 text-sm font-semibold leading-7 text-slate-500">
                Fill in your student information to start learning with us.
            </p>
        </div>

        <form action="{{ route('register.store') }}" method="POST" class="mt-8 space-y-6">
            @csrf

            @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="rounded-[24px] border border-slate-200 bg-slate-50/60 p-5 sm:p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                            Step 1
                        </p>
                        <h3 class="mt-1 text-lg font-black text-slate-900">
                            Account Information
                        </h3>
                    </div>

                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-black text-blue-700">
                        Required
                    </span>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="name" class="mb-2 block text-sm font-bold text-slate-700">
                            Full Name <span class="text-rose-500">*</span>
                        </label>

                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19a4 4 0 00-8 0m8 0H5m10 0h4M12 12a4 4 0 100-8 4 4 0 000 8z" />
                                </svg>
                            </span>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Full name"
                                required
                                class="focus-brand h-12 w-full rounded-2xl border border-slate-200 bg-white pl-12 pr-4 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-bold text-slate-700">
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
                                placeholder="email@example.com"
                                required
                                class="focus-brand h-12 w-full rounded-2xl border border-slate-200 bg-white pl-12 pr-4 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="mb-2 block text-sm font-bold text-slate-700">
                            Phone Number <span class="text-rose-500">*</span>
                        </label>

                        <div class="grid gap-3 grid-cols-[90px_1fr]">
                            <input
                                type="text"
                                name="country_code"
                                value="{{ old('country_code', '+62') }}"
                                required
                                class="focus-brand h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-black text-slate-700 shadow-sm">

                            <input
                                id="phone"
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="812xxxx"
                                required
                                class="focus-brand h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-bold text-slate-700">
                            Password <span class="text-rose-500">*</span>
                        </label>

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
                                placeholder="Min. 8 characters"
                                required
                                class="focus-brand h-12 w-full rounded-2xl border border-slate-200 bg-white pl-12 pr-12 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400">

                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" />
                                    <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-bold text-slate-700">
                            Confirm Password <span class="text-rose-500">*</span>
                        </label>

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
                                id="password_confirmation"
                                :type="show ? 'text' : 'password'"
                                name="password_confirmation"
                                placeholder="Repeat password"
                                required
                                class="focus-brand h-12 w-full rounded-2xl border border-slate-200 bg-white pl-12 pr-12 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400">

                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" />
                                    <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-[24px] border border-slate-200 bg-slate-50/60 p-5 sm:p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                            Step 2
                        </p>
                        <h3 class="mt-1 text-lg font-black text-slate-900">
                            Personal Details
                        </h3>
                    </div>

                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-black text-[var(--color-brand-gold)]">
                        Required
                    </span>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="place_of_birth" class="mb-2 block text-sm font-bold text-slate-700">
                            Place of Birth <span class="text-rose-500">*</span>
                        </label>

                        <input
                            id="place_of_birth"
                            type="text"
                            name="place_of_birth"
                            value="{{ old('place_of_birth') }}"
                            placeholder="City"
                            required
                            class="focus-brand h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400">
                    </div>

                    <div>
                        <label for="date_of_birth" class="mb-2 block text-sm font-bold text-slate-700">
                            Date of Birth <span class="text-rose-500">*</span>
                        </label>

                        <input
                            id="date_of_birth"
                            type="date"
                            name="date_of_birth"
                            value="{{ old('date_of_birth') }}"
                            required
                            class="focus-brand h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-900 shadow-sm">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-3 block text-sm font-bold text-slate-700">
                            Gender <span class="text-rose-500">*</span>
                        </label>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:border-[var(--color-brand-blue)]">
                                <input
                                    type="radio"
                                    name="gender"
                                    value="male"
                                    required
                                    @checked(old('gender')==='male' )
                                    class="border-slate-300 text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">
                                <span>Male</span>
                            </label>

                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:border-[var(--color-brand-blue)]">
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

                    <div class="sm:col-span-2">
                        <label for="address" class="mb-2 block text-sm font-bold text-slate-700">
                            Full Address <span class="text-rose-500">*</span>
                        </label>

                        <textarea
                            id="address"
                            name="address"
                            rows="3"
                            placeholder="Full address"
                            required
                            class="focus-brand w-full resize-none rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="rounded-[24px] border border-slate-200 bg-slate-50/60 p-5 sm:p-6">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                        Step 3
                    </p>
                    <h3 class="mt-1 text-lg font-black text-slate-900">
                        Additional Information
                    </h3>
                    <p class="mt-1 text-sm font-semibold text-slate-500">
                        Optional information to complete your student profile.
                    </p>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="occupation" class="mb-2 block text-sm font-bold text-slate-700">
                            Occupation
                        </label>

                        <input
                            id="occupation"
                            type="text"
                            name="occupation"
                            value="{{ old('occupation') }}"
                            placeholder="Student / Employee / etc"
                            class="focus-brand h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400">
                    </div>

                    <div>
                        <label for="institution" class="mb-2 block text-sm font-bold text-slate-700">
                            Institution
                        </label>

                        <input
                            id="institution"
                            type="text"
                            name="institution"
                            value="{{ old('institution') }}"
                            placeholder="School / University / Company"
                            class="focus-brand h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="instagram" class="mb-2 block text-sm font-bold text-slate-700">
                            Instagram
                        </label>

                        <input
                            id="instagram"
                            type="text"
                            name="instagram"
                            value="{{ old('instagram') }}"
                            placeholder="@username or profile link"
                            class="focus-brand h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400">
                    </div>
                </div>
            </div>

            <button
                type="submit"
                class="motion-button inline-flex h-14 w-full items-center justify-center rounded-2xl bg-[var(--color-brand-blue)] text-base font-black text-white shadow-[0_12px_24px_rgba(8,13,77,0.22)] transition hover:-translate-y-0.5 hover:opacity-95">
                Create Account
            </button>

            <div class="text-center">
                <p class="text-sm font-semibold text-slate-500">
                    WhatsApp confirmation is required for enrollment approval.
                </p>

                <p class="mt-4 text-sm font-semibold text-slate-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-black text-[var(--color-brand-blue)] hover:underline">
                        Sign in
                    </a>
                </p>

                <a href="{{ route('home') }}" class="mt-3 inline-block text-sm font-bold text-[#2457E6] hover:underline">
                    Back to Home
                </a>
            </div>
        </form>
    </div>
</div>