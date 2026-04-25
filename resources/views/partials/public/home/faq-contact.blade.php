@php
$latitude = '0.42298238201528715';
$longitude = '101.45628775972382';

$mapsHref = 'https://www.google.com/maps?q=' . $latitude . ',' . $longitude;
$whatsappHref = 'https://wa.me/6285274979336?text=' . urlencode('Hello Queens English Prestige, I want to ask about your programs.');
$instagramHref = 'https://www.instagram.com/queens_englishprestige?igsh=MTNibjc2YXFuY2duYg==';
$tiktokHref = 'https://www.tiktok.com/@prestigious.skills?_r=1&_t=ZS-95plPTwAhrY';
$emailAddress = 'hello@queensenglishprestige.com';
$emailHref = 'mailto:' . $emailAddress;
@endphp

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
            {{-- FAQ --}}
            <div>
                <h2 class="reveal text-2xl font-bold text-slate-900 md:text-3xl">
                    Frequently Asked <span class="text-[var(--color-brand-gold)]">Questions</span>
                </h2>

                <p class="reveal reveal-delay-1 mt-3 max-w-xl text-sm leading-7 text-slate-600">
                    Find quick answers about our programs, payment flow, and course access.
                </p>

                <div class="mt-8 space-y-3">
                    <div x-data="{ open: false }" class="reveal overflow-hidden rounded-xl border border-slate-300 bg-white">
                        <button
                            @click="open = !open"
                            type="button"
                            class="flex w-full items-center justify-between px-5 py-4 text-left">
                            <span class="text-sm font-semibold text-slate-900">
                                How long are the training programs?
                            </span>
                            <span class="text-slate-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''">⌄</span>
                        </button>

                        <div x-show="open" x-transition class="border-t border-slate-200 px-5 py-4 text-sm leading-7 text-slate-600">
                            Program duration depends on the course type and level. Some short programs
                            can be completed in a few weeks, while intensive or structured learning
                            programs may take longer.
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="reveal reveal-delay-1 overflow-hidden rounded-xl border border-slate-300 bg-white">
                        <button
                            @click="open = !open"
                            type="button"
                            class="flex w-full items-center justify-between px-5 py-4 text-left">
                            <span class="text-sm font-semibold text-slate-900">
                                Are sessions online or in-person?
                            </span>
                            <span class="text-slate-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''">⌄</span>
                        </button>

                        <div x-show="open" x-transition class="border-t border-slate-200 px-5 py-4 text-sm leading-7 text-slate-600">
                            We offer both online and offline learning options, depending on the
                            selected program and learning preference.
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="reveal reveal-delay-2 overflow-hidden rounded-xl border border-slate-300 bg-white">
                        <button
                            @click="open = !open"
                            type="button"
                            class="flex w-full items-center justify-between px-5 py-4 text-left">
                            <span class="text-sm font-semibold text-slate-900">
                                Do you provide official certifications?
                            </span>
                            <span class="text-slate-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''">⌄</span>
                        </button>

                        <div x-show="open" x-transition class="border-t border-slate-200 px-5 py-4 text-sm leading-7 text-slate-600">
                            Eligible students can receive certificates after completing the course
                            requirements and passing the final assessment.
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="reveal reveal-delay-3 overflow-hidden rounded-xl border border-slate-300 bg-white">
                        <button
                            @click="open = !open"
                            type="button"
                            class="flex w-full items-center justify-between px-5 py-4 text-left">
                            <span class="text-sm font-semibold text-slate-900">
                                How does payment confirmation work?
                            </span>
                            <span class="text-slate-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''">⌄</span>
                        </button>

                        <div x-show="open" x-transition class="border-t border-slate-200 px-5 py-4 text-sm leading-7 text-slate-600">
                            Orders are recorded first, then our admin confirms payment manually via
                            WhatsApp before course access is activated.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact --}}
            <div>
                <h2 class="reveal text-2xl font-bold text-slate-900 md:text-3xl">
                    Contact <span class="text-[var(--color-brand-gold)]">Us</span>
                </h2>

                <div class="mt-8 grid gap-5 sm:grid-cols-2">
                    <a href="{{ $emailHref }}" class="reveal flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-[var(--color-brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-18 8h18V8H3v8z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Email</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $emailAddress }}</p>
                        </div>
                    </a>

                    <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" class="reveal reveal-delay-1 flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-[var(--color-brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a2 2 0 011.95 1.56l.57 2.3a2 2 0 01-.58 1.95l-1.27 1.27a16 16 0 006.36 6.36l1.27-1.27a2 2 0 011.95-.58l2.3.57A2 2 0 0121 15.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">WhatsApp</p>
                            <p class="mt-1 text-sm text-slate-500">0852-7497-9336</p>
                        </div>
                    </a>

                    <a href="{{ $instagramHref }}" target="_blank" rel="noopener noreferrer" class="reveal reveal-delay-2 flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-[var(--color-brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="7" y="7" width="10" height="10" rx="2" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.5 8.5h.01" />
                                <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Instagram</p>
                            <p class="mt-1 text-sm text-slate-500">@queens_englishprestige</p>
                        </div>
                    </a>

                    <a href="{{ $tiktokHref }}" target="_blank" rel="noopener noreferrer" class="reveal reveal-delay-3 flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-[var(--color-brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19V6l12-2v13M9 17a2 2 0 11-4 0 2 2 0 014 0zm12-2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">TikTok</p>
                            <p class="mt-1 text-sm text-slate-500">@prestigious.skills</p>
                        </div>
                    </a>
                </div>

                <div class="reveal reveal-delay-2 mt-8 overflow-hidden rounded-xl border border-slate-200">
                    <div class="h-44 w-full">
                        <iframe
                            src="https://maps.google.com/maps?q={{ $latitude }},{{ $longitude }}&z=15&output=embed"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="h-full w-full"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <a href="{{ $mapsHref }}" target="_blank" rel="noopener noreferrer" class="reveal reveal-delay-3 mt-4 flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-[var(--color-brand-blue)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Location</p>
                        <p class="mt-1 text-sm text-slate-500">Jl. H. Abdul Kadir Abbas SH, Pandau Jaya, Kec. Siak Hulu, Kabupaten Kampar, Riau 28284</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>