@php
$latitude = '0.42298238201528715';
$longitude = '101.45628775972382';

$mapsHref = 'https://www.google.com/maps?q=' . $latitude . ',' . $longitude;
$whatsappHref = 'https://wa.me/6285274979336?text=' . urlencode('Hello Queens English Prestige, I want to ask about your programs.');
$emailHref = 'mailto:hello@queensenglishprestige.com';
@endphp

<section class="bg-[#f8f8f6]">
    <div class="mx-auto max-w-7xl px-4 pb-20 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-start">
            <div>
                <h2 class="text-4xl font-bold tracking-tight text-slate-900">
                    Our Location
                </h2>

                <div class="mt-8 overflow-hidden rounded-[24px] border border-[#d9e1ec] bg-white shadow-sm">
                    <div class="aspect-[16/10]">
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
            </div>

            <div class="space-y-5 lg:pt-[88px]">
                <div class="rounded-[24px] border border-[#d9e1ec] bg-white p-6 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-[18px] bg-[#eef4ff] text-[var(--color-brand-blue)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-5.6 7-11a7 7 0 10-14 0c0 5.4 7 11 7 11z" />
                            <circle cx="12" cy="10" r="2.5" stroke-width="1.8" />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-[22px] font-bold text-slate-900">
                        Location
                    </h3>

                    <p class="mt-3 text-base leading-7 text-slate-500">
                        Jalan Sukajadi, Pekanbaru, Riau
                    </p>

                    <p class="mt-5 text-base font-bold text-slate-900">
                        Mon–Fri: 9AM – 6PM
                    </p>

                    <a
                        href="{{ $mapsHref }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-brand-blue)] hover:underline">
                        <span>Open in Google Maps</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 17L17 7M17 7H9M17 7v8" />
                        </svg>
                    </a>
                </div>

                <a
                    href="{{ $whatsappHref }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-[16px] bg-[var(--color-brand-blue)] px-6 py-4 text-sm font-semibold text-white transition hover:opacity-90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 10.5A5.5 5.5 0 1112.5 16c-.95 0-1.85-.24-2.64-.66L6 16l.7-3.6A5.48 5.48 0 017 10.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 9.5c.2 1 1.3 2.2 2.3 2.5.4.1.8 0 1.1-.2l.8-.5" />
                    </svg>
                    <span>Chat via WhatsApp</span>
                </a>

                <a
                    href="{{ $emailHref }}"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-[16px] border border-[var(--color-brand-blue)] bg-white px-6 py-4 text-sm font-semibold text-[var(--color-brand-blue)] transition hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 12h14" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6l6 6-6 6" />
                    </svg>
                    <span>Send Email</span>
                </a>
            </div>
        </div>
    </div>
</section>