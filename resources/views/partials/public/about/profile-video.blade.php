@if ($profileVideo)
@php
$videoUrl = $profileVideo->video_file
? asset('storage/' . $profileVideo->video_file)
: null;
@endphp

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-12 lg:px-8 lg:py-16">
        <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="reveal order-2 lg:order-1">
                <div class="relative mx-auto max-w-xl lg:mx-0">
                    <div class="absolute -left-4 -top-4 hidden h-28 w-28 rounded-full bg-[#EEF5FF] lg:block"></div>
                    <div class="absolute -bottom-4 -right-4 hidden h-24 w-24 rounded-full bg-yellow-50 lg:block"></div>

                    <div class="motion-card relative overflow-hidden rounded-[26px] border border-slate-200 bg-white p-2 shadow-sm">
                        <div class="overflow-hidden rounded-[20px] bg-slate-900">
                            @if ($videoUrl)
                            <video
                                src="{{ $videoUrl }}"
                                autoplay
                                muted
                                loop
                                playsinline
                                preload="metadata"
                                controls
                                class="motion-image aspect-video w-full bg-slate-900 object-cover">
                                Your browser does not support the video tag.
                            </video>
                            @else
                            <div class="flex aspect-video items-center justify-center bg-slate-100 px-6 text-center">
                                <p class="text-sm font-semibold text-slate-500">
                                    Profile video will be available soon.
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="reveal reveal-delay-1 order-1 lg:order-2">
                <div class="max-w-xl lg:ml-auto">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-[var(--color-brand-gold)]">
                        Profile Video
                    </p>

                    <h2 class="mt-4 text-3xl font-bold leading-tight text-[var(--color-brand-blue)] md:text-4xl">
                        Get to Know Queens English Prestige
                    </h2>

                    <div class="mt-4 h-1 w-16 rounded-full bg-[var(--color-brand-gold)]"></div>

                    <p class="mt-5 text-sm leading-7 text-slate-600 md:text-base md:leading-8">
                        {{ $profileVideo->description ?: 'Discover our learning environment, teaching approach, and commitment to helping students build real English communication skills.' }}
                    </p>

                    @if ($profileVideo->title)
                    <p class="mt-5 text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-400">
                        {{ $profileVideo->title }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif