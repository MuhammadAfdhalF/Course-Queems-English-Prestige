@php
$whatsappNumber = '6285274979336';
$whatsappHref = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode('Hello Queens English Prestige, I want to ask about your programs.');

$instagramHref = 'https://www.instagram.com/queens_englishprestige?igsh=MTNibjc2YXFuY2duYg==';
$tiktokHref = 'https://www.tiktok.com/@prestigious.skills?_r=1&_t=ZS-95plPTwAhrY';
$emailAddress = 'hello@queens.com';
$emailHref = 'mailto:' . $emailAddress;
@endphp

<section class="bg-[#f8f8f6]">
    <div class="mx-auto max-w-7xl px-4 py-12 lg:px-8 lg:py-14">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <x-public.contact-card
                title="WhatsApp"
                description="Direct access — primary contact for immediate course inquiries."
                value="0852-7497-9336"
                :href="$whatsappHref"
                featured>
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 10.5A5.5 5.5 0 1112.5 16c-.95 0-1.85-.24-2.64-.66L6 16l.7-3.6A5.48 5.48 0 017 10.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 9.5c.2 1 1.3 2.2 2.3 2.5.4.1.8 0 1.1-.2l.8-.5" />
                    </svg>
                </x-slot:icon>
            </x-public.contact-card>

            <x-public.contact-card
                title="Email"
                description="General inquiries and student support community."
                value="hello@queens.com"
                secondaryValue="@elite.english"
                :href="$emailHref"
                isEmail>
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7l8.2 5.47a1.5 1.5 0 001.66 0L21 7" />
                        <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.8" />
                    </svg>
                </x-slot:icon>
            </x-public.contact-card>

            <x-public.contact-card
                title="Instagram"
                description="Visit our Instagram for updates, activities, and learning highlights."
                value="@queens_englishprestige"
                :href="$instagramHref">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="4" y="4" width="16" height="16" rx="4" stroke-width="1.8" />
                        <circle cx="12" cy="12" r="3.5" stroke-width="1.8" />
                        <circle cx="17" cy="7" r="1" fill="currentColor" stroke="none" />
                    </svg>
                </x-slot:icon>
            </x-public.contact-card>

            <x-public.contact-card
                title="TikTok"
                description="Visit our TikTok to explore our story and engaging short-form content."
                value="@prestigious.skills"
                :href="$tiktokHref">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16.5 3c.3 1.7 1.3 3 3 3.7v2.5c-1.3 0-2.4-.4-3.4-1.1v6.1a5.2 5.2 0 11-5.2-5.2c.3 0 .6 0 .9.1v2.7a2.8 2.8 0 10 1.9 2.7V3h2.8z" />
                    </svg>
                </x-slot:icon>
            </x-public.contact-card>
        </div>
    </div>
</section>