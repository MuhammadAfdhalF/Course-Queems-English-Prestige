@php
$socialLinks = $contact?->socialLinks ?? collect();

$getSocialLink = function ($platform) use ($socialLinks) {
return $socialLinks->first(function ($item) use ($platform) {
return strtolower($item->platform ?? '') === strtolower($platform);
});
};

$instagram = $getSocialLink('instagram');
$tiktok = $getSocialLink('tiktok');

$emailAddress = $contact?->email ?: 'info@queensenglish.com';
$emailHref = $contact?->email_link ?: ('mailto:' . $emailAddress);

$whatsappNumber = $contact?->whatsapp ?: $contact?->phone ?: '085274979336';

$defaultWhatsappMessage = 'Hello Queens English Prestige, I saw your website and I’m interested in joining one of your English programs. Could you please help me with the course details, schedule, and registration information?';

if (filled($contact?->whatsapp_link) && $contact->whatsapp_link !== '#') {
$whatsappHref = str_contains($contact->whatsapp_link, 'text=')
? $contact->whatsapp_link
: $contact->whatsapp_link . (str_contains($contact->whatsapp_link, '?') ? '&' : '?') . 'text=' . urlencode($defaultWhatsappMessage);
} else {
$cleanWhatsappNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);

if (str_starts_with($cleanWhatsappNumber, '0')) {
$cleanWhatsappNumber = '62' . substr($cleanWhatsappNumber, 1);
}

$whatsappHref = 'https://wa.me/' . $cleanWhatsappNumber . '?text=' . urlencode($defaultWhatsappMessage);
}

$instagramUrl = $instagram?->url ?: 'https://www.instagram.com/queens_englishprestige?igsh=MTNibjc2YXFuY2duYg==';
$instagramLabel = $instagram?->label ?: $instagram?->username ?: '@queens_englishprestige';

$tiktokUrl = $tiktok?->url ?: 'https://www.tiktok.com/@prestigious.skills?_r=1&_t=ZS-95plPTwAhrY';
$tiktokLabel = $tiktok?->label ?: $tiktok?->username ?: '@prestigious.skills';

$address = $contact?->address ?: 'Jl. H. Abdul Kadir Abbas SH, Pandau Jaya, Kec. Siak Hulu, Kabupaten Kampar, Riau 28284';

$latitude = $contact?->latitude;
$longitude = $contact?->longitude;

$hasCoordinates = filled($latitude) && filled($longitude);

$adminMapUrl = $contact?->map_embed_url;

$mapEmbedUrl = null;

if (filled($adminMapUrl) && str_contains($adminMapUrl, '/maps/embed')) {
$mapEmbedUrl = $adminMapUrl;
} elseif ($hasCoordinates) {
$mapEmbedUrl = 'https://maps.google.com/maps?q=' . $latitude . ',' . $longitude . '&z=15&output=embed';
}

$mapsHref = $hasCoordinates
? 'https://www.google.com/maps?q=' . $latitude . ',' . $longitude
: '#';

$fallbackFaqs = collect([
[
'question' => 'How long are the training programs?',
'answer' => 'Program duration depends on the course type and level. Short programs can be completed in a few weeks, while intensive or structured learning programs may take longer.',
],
[
'question' => 'Are sessions online or in-person?',
'answer' => 'We offer both online and offline learning options, depending on the selected program and learning preference.',
],
[
'question' => 'Do you provide official certifications?',
'answer' => 'Eligible students can receive certificates after completing the course requirements and passing the final assessment.',
],
[
'question' => 'How does payment confirmation work?',
'answer' => 'Orders are recorded first, then our admin confirms payment manually via WhatsApp before course access is activated.',
],
]);

$faqItems = ($faqs ?? collect())->isNotEmpty()
? $faqs
: $fallbackFaqs;
@endphp

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-[1fr_0.95fr] lg:gap-16">
            {{-- FAQ --}}
            <div>
                <x-public.section-title class="reveal text-2xl md:text-3xl">
                    Frequently Asked [gold]Questions[/gold]
                </x-public.section-title>

                <p class="reveal reveal-delay-1 mt-3 max-w-xl text-sm leading-7 text-slate-600 md:text-base">
                    Find quick answers about our programs, payment flow, and course access.
                </p>

                <div class="mt-8 space-y-4">
                    @foreach ($faqItems->take(5) as $index => $faq)
                    @php
                    $question = is_array($faq) ? $faq['question'] : $faq->question;
                    $answer = is_array($faq) ? $faq['answer'] : $faq->answer;

                    $delayClass = match ($index % 4) {
                    1 => 'reveal-delay-1',
                    2 => 'reveal-delay-2',
                    3 => 'reveal-delay-3',
                    default => '',
                    };
                    @endphp

                    <div
                        x-data="{ open: false }"
                        class="reveal {{ $delayClass }} overflow-hidden rounded-2xl border border-slate-300 bg-white transition duration-300 hover:border-[var(--color-brand-blue)]">
                        <button
                            type="button"
                            @click="open = !open"
                            class="flex w-full items-center justify-between gap-4 px-5 py-5 text-left">
                            <span class="text-sm font-bold leading-6 text-slate-900 md:text-base">
                                {{ $question }}
                            </span>

                            <span
                                class="shrink-0 text-[var(--color-brand-blue)] transition-transform duration-300"
                                :class="open ? 'rotate-180' : ''">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>

                        <div
                            x-show="open"
                            x-transition
                            class="border-t border-slate-300 bg-slate-50/80 px-5 pb-5 pt-4">
                            <p class="whitespace-pre-line text-sm leading-7 text-slate-600">
                                {{ $answer }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Contact --}}
            <div>
                <x-public.section-title class="reveal text-2xl md:text-3xl">
                    Contact [gold]Us[/gold]
                </x-public.section-title>

                <p class="reveal reveal-delay-1 mt-3 max-w-xl text-sm leading-7 text-slate-600 md:text-base">
                    Need help choosing a program? Reach us through WhatsApp, email, or social media.
                </p>

                <div class="mt-8 grid gap-5 sm:grid-cols-2">
                    <a href="{{ $emailHref }}" class="reveal flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[var(--color-brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l8.4 5.6a1.1 1.1 0 001.2 0L21 8" />
                                <rect x="3" y="6" width="18" height="12" rx="2" stroke-width="1.8" />
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900">Email</p>
                            <p class="mt-1 truncate text-sm text-slate-500">{{ $emailAddress }}</p>
                        </div>
                    </a>

                    <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" class="reveal reveal-delay-1 flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#fff6da] text-[var(--color-brand-gold)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.04 2a9.86 9.86 0 00-8.45 14.94L2.5 21.5l4.68-1.06A9.86 9.86 0 1012.04 2zm0 1.8a8.06 8.06 0 014.12 14.99 8.05 8.05 0 01-8.38-.23l-.32-.2-2.55.58.59-2.45-.22-.34A8.06 8.06 0 0112.04 3.8zm-3.02 3.9c-.18 0-.47.07-.72.34-.25.27-.95.93-.95 2.26s.98 2.62 1.12 2.8c.14.18 1.9 3.04 4.71 4.14 2.34.92 2.82.74 3.33.69.51-.05 1.65-.67 1.88-1.32.23-.65.23-1.21.16-1.32-.07-.11-.25-.18-.53-.32-.28-.14-1.65-.81-1.9-.9-.25-.09-.44-.14-.62.14-.18.28-.71.9-.87 1.08-.16.18-.32.2-.6.07-.28-.14-1.18-.44-2.24-1.39-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.12-.12.28-.32.42-.48.14-.16.18-.28.28-.46.09-.18.05-.34-.02-.48-.07-.14-.62-1.49-.85-2.04-.22-.53-.45-.46-.62-.47h-.53z" />
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-bold text-slate-900">WhatsApp</p>

                                <span class="inline-flex items-center rounded-full bg-[#fff6da] px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-[0.08em] text-[var(--color-brand-gold)]">
                                    Primary
                                </span>
                            </div>

                            <p class="mt-1 text-sm text-slate-500">{{ $whatsappNumber }}</p>
                        </div>
                    </a>

                    <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="reveal reveal-delay-2 flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[var(--color-brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="5" y="5" width="14" height="14" rx="4" stroke-width="1.8" />
                                <circle cx="12" cy="12" r="3.2" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16.7 7.3h.01" />
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900">Instagram</p>
                            <p class="mt-1 truncate text-sm text-slate-500">{{ $instagramLabel }}</p>
                        </div>
                    </a>

                    <a href="{{ $tiktokUrl }}" target="_blank" rel="noopener noreferrer" class="reveal reveal-delay-3 flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[var(--color-brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 4v10.5a4.5 4.5 0 11-4.5-4.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 4c1.1 2.8 2.8 4.4 5.5 4.8" />
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900">TikTok</p>
                            <p class="mt-1 truncate text-sm text-slate-500">{{ $tiktokLabel }}</p>
                        </div>
                    </a>
                </div>

                <div class="reveal reveal-delay-2 mt-8 overflow-hidden rounded-xl border border-slate-200">
                    <div class="h-44 w-full">
                        @if ($mapEmbedUrl)
                        <iframe
                            src="{{ $mapEmbedUrl }}"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="h-full w-full"
                            allowfullscreen>
                        </iframe>
                        @else
                        <div class="flex h-full w-full items-center justify-center bg-slate-50 px-6 text-center">
                            <p class="text-sm font-semibold text-slate-500">
                                Map will be available soon.
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <a
                    href="{{ $mapsHref }}"
                    @if ($mapsHref !=='#' ) target="_blank" rel="noopener noreferrer" @endif
                    class="reveal reveal-delay-3 mt-5 flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#fff6da] text-[var(--color-brand-gold)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-4.8 7-11a7 7 0 10-14 0c0 6.2 7 11 7 11z" />
                            <circle cx="12" cy="10" r="2.5" stroke-width="1.8" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-slate-900">Location</p>
                        <p class="mt-1 text-sm leading-7 text-slate-500">
                            {{ $address }}
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>