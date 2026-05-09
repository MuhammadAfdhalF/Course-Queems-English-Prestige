@php
$defaultWhatsappMessage = 'Hello Queens English Prestige, I saw your website and I am interested in your programs. Could you please share more information?';

if (! function_exists('homeWhatsappUrl')) {
function homeWhatsappUrl(?string $url, string $message): string
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

$latitude = $contact?->latitude;
$longitude = $contact?->longitude;
$hasCoordinates = filled($latitude) && filled($longitude);

$mapsHref = $hasCoordinates
? 'https://www.google.com/maps?q=' . $latitude . ',' . $longitude
: '#';

$adminMapUrl = $contact?->map_embed_url;
$mapEmbedUrl = null;

if (filled($adminMapUrl) && str_contains($adminMapUrl, '/maps/embed')) {
$mapEmbedUrl = $adminMapUrl;
} elseif ($hasCoordinates) {
$mapEmbedUrl = 'https://maps.google.com/maps?q=' . $latitude . ',' . $longitude . '&z=15&output=embed';
}

$whatsappHref = homeWhatsappUrl($contact?->whatsapp_link, $defaultWhatsappMessage);
$whatsappLabel = $contact?->whatsapp_label ?: 'WhatsApp';

$emailHref = $contact?->email_link ?: '#';
$emailLabel = $contact?->email_label ?: 'Email';

$address = $contact?->address ?: 'Jl. H. Abdul Kadir Abbas SH, Pandau Jaya, Kec. Siak Hulu, Kabupaten Kampar, Riau 28284';

$fallbackFaqs = collect([
[
'question' => 'How long are the training programs?',
'answer' => 'Program duration depends on the course type and level. Some short programs can be completed in a few weeks, while intensive or structured learning programs may take longer.',
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
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
            <div>
                <h2 class="reveal text-2xl font-bold text-slate-900 md:text-3xl">
                    Frequently Asked <span class="text-[var(--color-brand-gold)]">Questions</span>
                </h2>

                <p class="reveal reveal-delay-1 mt-3 max-w-xl text-sm leading-7 text-slate-600">
                    Find quick answers about our programs, payment flow, and course access.
                </p>

                <div class="mt-8 space-y-3">
                    @foreach ($faqItems as $index => $faq)
                    @php
                    $delayClass = match ($index % 4) {
                    1 => 'reveal-delay-1',
                    2 => 'reveal-delay-2',
                    3 => 'reveal-delay-3',
                    default => '',
                    };

                    $question = is_array($faq) ? $faq['question'] : $faq->question;
                    $answer = is_array($faq) ? $faq['answer'] : $faq->answer;
                    @endphp

                    <div x-data="{ open: false }" class="reveal {{ $delayClass }} overflow-hidden rounded-xl border border-slate-300 bg-white">
                        <button
                            @click="open = !open"
                            type="button"
                            class="flex w-full items-center justify-between px-5 py-4 text-left">
                            <span class="text-sm font-semibold text-slate-900">
                                {{ $question }}
                            </span>
                            <span class="text-slate-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''">⌄</span>
                        </button>

                        <div x-show="open" x-transition class="border-t border-slate-200 px-5 py-4 text-sm leading-7 text-slate-600">
                            {!! nl2br(e($answer)) !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h2 class="reveal text-2xl font-bold text-slate-900 md:text-3xl">
                    Contact <span class="text-[var(--color-brand-gold)]">Us</span>
                </h2>

                <div class="mt-8 grid gap-5 sm:grid-cols-2">
                    @if ($emailHref !== '#')
                    <a href="{{ $emailHref }}" class="reveal flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-[var(--color-brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-18 8h18V8H3v8z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Email</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $emailLabel }}</p>
                        </div>
                    </a>
                    @endif

                    @if ($whatsappHref !== '#')
                    <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" class="reveal reveal-delay-1 flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-[var(--color-brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a2 2 0 011.95 1.56l.57 2.3a2 2 0 01-.58 1.95l-1.27 1.27a16 16 0 006.36 6.36l1.27-1.27a2 2 0 011.95-.58l2.3.57A2 2 0 0121 15.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">WhatsApp</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $whatsappLabel }}</p>
                        </div>
                    </a>
                    @endif

                    @foreach (($contact?->socialLinks ?? collect())->take(2) as $socialLink)
                    <a href="{{ $socialLink->url }}" target="_blank" rel="noopener noreferrer" class="reveal reveal-delay-2 flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-[var(--color-brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.5 10.5L21 3m0 0h-6m6 0v6" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.5 5H6a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3v-4.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $socialLink->platform ?: 'Social Media' }}
                            </p>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $socialLink->label ?: $socialLink->url }}
                            </p>
                        </div>
                    </a>
                    @endforeach
                </div>

                @if ($mapEmbedUrl)
                <div class="reveal reveal-delay-2 mt-8 overflow-hidden rounded-xl border border-slate-200">
                    <div class="h-44 w-full">
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
                </div>
                @endif

                <a
                    href="{{ $mapsHref }}"
                    @if ($mapsHref !=='#' ) target="_blank" rel="noopener noreferrer" @endif
                    class="reveal reveal-delay-3 mt-4 flex items-start gap-3 rounded-xl transition hover:bg-slate-50/70">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-[var(--color-brand-blue)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Location</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $address }}</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>