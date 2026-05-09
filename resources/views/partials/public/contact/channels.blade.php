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

$whatsappLabel = $contact?->whatsapp_label ?: 'Contact us via WhatsApp';
$whatsappHref = contactWhatsappUrl($contact?->whatsapp_link, $defaultWhatsappMessage);

$emailLabel = $contact?->email_label ?: 'Contact us via Email';
$emailHref = $contact?->email_link ?: '#';

if (! function_exists('contactSocialDescription')) {
function contactSocialDescription(?string $platform): string
{
return match ($platform) {
'instagram' => 'Visit our Instagram for updates, activities, and learning highlights.',
'tiktok' => 'Visit our TikTok to explore our story and engaging short-form content.',
'facebook' => 'Connect with us on Facebook for updates and announcements.',
'youtube' => 'Watch our videos, activities, and learning highlights on YouTube.',
'linkedin' => 'Connect with us professionally on LinkedIn.',
default => 'Visit this channel for more information and updates.',
};
}
}

if (! function_exists('contactSocialTitle')) {
function contactSocialTitle(?string $platform): string
{
return match ($platform) {
'instagram' => 'Instagram',
'tiktok' => 'TikTok',
'facebook' => 'Facebook',
'youtube' => 'YouTube',
'linkedin' => 'LinkedIn',
default => 'Social Link',
};
}
}
@endphp

<section class="bg-[#f8f8f6]">
    <div class="mx-auto max-w-7xl px-4 py-12 lg:px-8 lg:py-14">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="reveal">
                <x-public.contact-card
                    title="WhatsApp"
                    description="Direct access — primary contact for immediate course inquiries."
                    :value="$whatsappLabel"
                    :href="$whatsappHref"
                    featured>
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 10.5A5.5 5.5 0 1112.5 16c-.95 0-1.85-.24-2.64-.66L6 16l.7-3.6A5.48 5.48 0 017 10.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 9.5c.2 1 1.3 2.2 2.3 2.5.4.1.8 0 1.1-.2l.8-.5" />
                        </svg>
                    </x-slot:icon>
                </x-public.contact-card>
            </div>

            <div class="reveal reveal-delay-1">
                <x-public.contact-card
                    title="Email"
                    description="General inquiries and student support community."
                    :value="$emailLabel"
                    :href="$emailHref"
                    isEmail>
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7l8.2 5.47a1.5 1.5 0 001.66 0L21 7" />
                            <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.8" />
                        </svg>
                    </x-slot:icon>
                </x-public.contact-card>
            </div>

            @foreach ($socialLinks ?? [] as $socialLink)
            @php
            $delayClass = match (($loop->index + 2) % 4) {
            1 => 'reveal-delay-1',
            2 => 'reveal-delay-2',
            3 => 'reveal-delay-3',
            default => '',
            };

            $platformTitle = contactSocialTitle($socialLink->platform);
            $platformDescription = contactSocialDescription($socialLink->platform);
            $platformValue = $socialLink->label ?: $socialLink->url;
            @endphp

            <div class="reveal {{ $delayClass }}">
                <x-public.contact-card
                    :title="$platformTitle"
                    :description="$platformDescription"
                    :value="$platformValue"
                    :href="$socialLink->url">
                    <x-slot:icon>
                        @if ($socialLink->platform === 'instagram')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <rect x="4" y="4" width="16" height="16" rx="4" stroke-width="1.8" />
                            <circle cx="12" cy="12" r="3.5" stroke-width="1.8" />
                            <circle cx="17" cy="7" r="1" fill="currentColor" stroke="none" />
                        </svg>
                        @elseif ($socialLink->platform === 'tiktok')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16.5 3c.3 1.7 1.3 3 3 3.7v2.5c-1.3 0-2.4-.4-3.4-1.1v6.1a5.2 5.2 0 11-5.2-5.2c.3 0 .6 0 .9.1v2.7a2.8 2.8 0 10 1.9 2.7V3h2.8z" />
                        </svg>
                        @elseif ($socialLink->platform === 'facebook')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 8h2.5V4.2A18.6 18.6 0 0013 4c-3.5 0-5.8 2.1-5.8 6v3.4H3.5V18h3.7v6h4.6v-6h3.6l.6-4.6h-4.2v-3c0-1.3.4-2.4 2.2-2.4z" />
                        </svg>
                        @elseif ($socialLink->platform === 'youtube')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21.6 7.2a3 3 0 00-2.1-2.1C17.7 4.6 12 4.6 12 4.6s-5.7 0-7.5.5a3 3 0 00-2.1 2.1A31.2 31.2 0 002 12a31.2 31.2 0 00.4 4.8 3 3 0 002.1 2.1c1.8.5 7.5.5 7.5.5s5.7 0 7.5-.5a3 3 0 002.1-2.1A31.2 31.2 0 0022 12a31.2 31.2 0 00-.4-4.8zM10 15.4V8.6l5.8 3.4L10 15.4z" />
                        </svg>
                        @elseif ($socialLink->platform === 'linkedin')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.5 8.8H3V21h3.5V8.8zM4.8 3A2 2 0 104.7 7a2 2 0 00.1-4zM21 14.1c0-3.3-1.8-5.4-4.6-5.4a4 4 0 00-3.6 2h-.1V8.8H9.4V21h3.5v-6.1c0-1.6.3-3.1 2.3-3.1s2 1.8 2 3.2v6H21v-6.9z" />
                        </svg>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.5 10.5L21 3m0 0h-6m6 0v6" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.5 5H6a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3v-4.5" />
                        </svg>
                        @endif
                    </x-slot:icon>
                </x-public.contact-card>
            </div>
            @endforeach
        </div>
    </div>
</section>