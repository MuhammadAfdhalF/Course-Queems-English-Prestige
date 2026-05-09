@php
$defaultWhatsappMessage = 'Hello Queens English Prestige, I saw your website and I’m interested in joining one of your English programs. Could you please help me with the course details, schedule, and registration information?';

if (! function_exists('contactWhatsappUrl')) {
    function contactWhatsappUrl(?string $url, string $message): string
    {
        if (! filled($url) || $url === '#') {
            return '#';
        }

        if (str_contains($url, 'text=')) {
            return $url;
        }

        $separator = str_contains($url, '?') ? '&' : '?';

        return $url . $separator . 'text=' . urlencode($message);
    }
}

$address = $contact?->address ?: 'Address will be available soon.';
$operationalHours = $contact?->operational_hours ?: 'Operational hours will be available soon.';

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

$whatsappHref = contactWhatsappUrl($contact?->whatsapp_link, $defaultWhatsappMessage);
$emailHref = $contact?->email_link ?: '#';
@endphp

<section class="bg-[#f8f8f6]">
    <div class="mx-auto max-w-7xl px-4 pb-20 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-start">
            <div class="reveal">
                <h2 class="text-4xl font-bold tracking-tight text-slate-900">
                    Our Location
                </h2>

                <div class="mt-8 overflow-hidden rounded-[24px] border border-[#d9e1ec] bg-white shadow-sm">
                    @if ($mapEmbedUrl)
                        <div class="aspect-[16/10]">
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
                        </div>
                    @else
                        <div class="flex aspect-[16/10] items-center justify-center bg-slate-50 px-6 text-center">
                            <p class="text-sm font-semibold text-slate-500">
                                Map will be available soon.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-5 lg:pt-[88px]">
                <div class="reveal reveal-delay-1 rounded-[24px] border border-[#d9e1ec] bg-white p-6 shadow-sm">
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
                        {{ $address }}
                    </p>

                    <p class="mt-5 text-base font-bold text-slate-900">
                        {{ $operationalHours }}
                    </p>

                    @if ($mapsHref !== '#')
                    <a
                        href="{{ $mapsHref }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-brand-blue)] hover:underline">
                        <span>Open in Google Maps</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="motion-link-arrow h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 17L17 7M17 7H9M17 7v8" />
                        </svg>
                    </a>
                    @endif
                </div>

                @if ($whatsappHref !== '#')
                <a
                    href="{{ $whatsappHref }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="reveal reveal-delay-2 motion-button inline-flex w-full items-center justify-center gap-2 rounded-[16px] bg-[var(--color-brand-blue)] px-6 py-4 text-sm font-semibold text-white transition hover:opacity-90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 10.5A5.5 5.5 0 1112.5 16c-.95 0-1.85-.24-2.64-.66L6 16l.7-3.6A5.48 5.48 0 017 10.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 9.5c.2 1 1.3 2.2 2.3 2.5.4.1.8 0 1.1-.2l.8-.5" />
                    </svg>
                    <span>Chat via WhatsApp</span>
                </a>
                @endif

                @if ($emailHref !== '#')
                <a
                    href="{{ $emailHref }}"
                    class="reveal reveal-delay-3 motion-button inline-flex w-full items-center justify-center gap-2 rounded-[16px] border border-[var(--color-brand-blue)] bg-white px-6 py-4 text-sm font-semibold text-[var(--color-brand-blue)] transition hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 12h14" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6l6 6-6 6" />
                    </svg>
                    <span>Send Email</span>
                </a>
                @endif
            </div>
        </div>
    </div>
</section>