@extends('layouts.public')

@section('content')
@php
$eventDate = $post->event_date
    ? $post->event_date->format('M d, Y')
    : null;

$additionalImages = $post->images
    ->filter(function ($image) use ($post) {
        return !$post->thumbnail || $image->image !== $post->thumbnail;
    })
    ->values()
    ->map(function ($image) {
        return [
            'id' => $image->id,
            'url' => asset('storage/' . $image->image),
            'caption' => $image->caption,
        ];
    })
    ->values();
@endphp

<div
    x-data="{
        featuredPreviewOpen: false,
        lightboxOpen: false,
        lightboxIndex: 0,
        zoomLevel: 1,
        additionalImages: @js($additionalImages),

        openFeaturedPreview() {
            this.featuredPreviewOpen = true;
            document.body.classList.add('overflow-hidden');
        },

        closeFeaturedPreview() {
            this.featuredPreviewOpen = false;
            document.body.classList.remove('overflow-hidden');
        },

        openLightbox(index) {
            this.lightboxIndex = index;
            this.zoomLevel = 1;
            this.lightboxOpen = true;
            document.body.classList.add('overflow-hidden');
        },

        closeLightbox() {
            this.lightboxOpen = false;
            this.zoomLevel = 1;
            document.body.classList.remove('overflow-hidden');
        },

        nextLightbox() {
            if (!this.lightboxOpen || this.additionalImages.length === 0) return;
            this.zoomLevel = 1;
            this.lightboxIndex = (this.lightboxIndex + 1) % this.additionalImages.length;
        },

        previousLightbox() {
            if (!this.lightboxOpen || this.additionalImages.length === 0) return;
            this.zoomLevel = 1;
            this.lightboxIndex = (this.lightboxIndex - 1 + this.additionalImages.length) % this.additionalImages.length;
        },

        zoomIn() {
            this.zoomLevel = Math.min(this.zoomLevel + 0.25, 3);
        },

        zoomOut() {
            this.zoomLevel = Math.max(this.zoomLevel - 0.25, 0.5);
        },

        resetZoom() {
            this.zoomLevel = 1;
        }
    }"
    @keydown.escape.window="closeLightbox(); closeFeaturedPreview();"
    @keydown.left.window="previousLightbox()"
    @keydown.right.window="nextLightbox()">

    <section class="relative overflow-hidden border-b border-slate-200 bg-[#F7FAFD]">
        <div class="absolute inset-0">
            <img
                src="{{ asset('images/about-hero-bg.png') }}"
                alt="News Detail Background"
                class="h-full w-full object-cover opacity-100"
                onerror="this.style.display='none';">
            <div class="absolute inset-0 bg-white/45"></div>
        </div>

        <div class="relative mx-auto max-w-5xl px-4 py-16 text-center lg:px-8 lg:py-20">
            <div class="reveal flex flex-wrap items-center justify-center gap-3">
                <span class="inline-flex rounded-md bg-[#f8efcf] px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.12em] text-[var(--color-brand-gold)]">
                    {{ $typeLabel }}
                </span>

                <span class="text-sm font-semibold text-slate-500">
                    {{ $displayDate }}
                </span>
            </div>

            <h1 class="reveal reveal-delay-1 mx-auto mt-5 max-w-4xl text-4xl font-bold leading-tight text-[var(--color-brand-blue)] md:text-5xl">
                {{ $post->title }}
            </h1>

            @if ($post->excerpt)
            <p class="reveal reveal-delay-2 mx-auto mt-5 max-w-3xl text-base leading-8 text-slate-500 md:text-lg">
                {{ $post->excerpt }}
            </p>
            @endif

            <div class="reveal reveal-delay-3 mt-8">
                <a
                    href="{{ route('news') }}"
                    class="inline-flex items-center gap-2 text-sm font-bold text-[var(--color-brand-blue)] hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M13 5l7 7-7 7" />
                    </svg>
                    Back to News
                </a>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-12 lg:px-8 lg:py-16">
            @if ($post->thumbnail)
            <div class="reveal mx-auto max-w-6xl">
                <button
                    type="button"
                    @click="openFeaturedPreview()"
                    class="group relative block w-full overflow-hidden rounded-[28px] border border-slate-200 bg-white p-3 text-left shadow-sm transition duration-300 hover:shadow-md">
                    <div class="relative overflow-hidden rounded-[22px] bg-slate-100">
                        <img
                            src="{{ asset('storage/' . $post->thumbnail) }}"
                            alt="{{ $post->title }}"
                            class="aspect-[16/7] w-full rounded-[22px] object-cover md:aspect-[16/6]">

                        <div class="absolute inset-0 flex items-center justify-center bg-slate-950/20 opacity-0 transition duration-300 group-hover:opacity-100">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/95 px-4 py-2 text-xs font-bold text-[var(--color-brand-blue)] shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                                </svg>
                                Click to View Image
                            </span>
                        </div>
                    </div>
                </button>
            </div>
            @endif

            <article class="reveal reveal-delay-1 mx-auto mt-10 max-w-6xl rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8 lg:p-10">
                <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex rounded-md bg-[#f8efcf] px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.12em] text-[var(--color-brand-gold)]">
                            {{ $typeLabel }}
                        </span>

                        <span class="text-sm font-semibold text-slate-500">
                            Published: {{ $displayDate }}
                        </span>
                    </div>

                    @if ($eventDate)
                    <span class="inline-flex rounded-md bg-blue-50 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.12em] text-[var(--color-brand-blue)]">
                        Event: {{ $eventDate }}
                    </span>
                    @endif
                </div>

                @if ($post->content)
                <div class="rich-text-content text-base leading-8 text-slate-700">
                    {!! $post->content !!}
                </div>
                @else
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-base leading-8 text-slate-500">
                        Content will be available soon.
                    </p>
                </div>
                @endif
            </article>

            @if ($additionalImages->isNotEmpty())
            <div class="reveal reveal-delay-2 mx-auto mt-14 max-w-6xl">
                <div class="border-t border-slate-200 pt-10">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-brand-gold)]">
                                Gallery
                            </p>

                            <h2 class="mt-3 text-2xl font-bold text-slate-900">
                                More Photos ({{ $additionalImages->count() }})
                            </h2>
                        </div>

                        <p class="text-sm text-slate-500">
                            Click any photo to view in full resolution.
                        </p>
                    </div>

                    <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($additionalImages as $idx => $img)
                        <button
                            type="button"
                            @click="openLightbox({{ $idx }})"
                            class="group overflow-hidden rounded-[22px] border border-slate-200 bg-white p-3 text-left shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">
                            <div class="relative overflow-hidden rounded-[16px] bg-slate-100">
                                <img
                                    src="{{ $img['url'] }}"
                                    alt="{{ $img['caption'] ?: $post->title }}"
                                    class="aspect-[4/3] w-full object-cover transition duration-300 group-hover:scale-105">

                                <div class="absolute inset-0 flex items-end justify-center bg-gradient-to-t from-slate-950/55 via-slate-950/10 to-transparent opacity-0 transition duration-300 group-hover:opacity-100">
                                    <span class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/95 px-4 py-2 text-xs font-bold text-[var(--color-brand-blue)] shadow-sm">
                                        Preview Photo
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            @if ($img['caption'])
                            <p class="px-1 pt-3 text-center text-sm font-medium leading-6 text-slate-500">
                                {{ $img['caption'] }}
                            </p>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <div class="reveal reveal-delay-3 mx-auto mt-12 max-w-6xl border-t border-slate-200 pt-8">
                <a
                    href="{{ route('news') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                    Back to News
                </a>
            </div>
        </div>
    </section>

    @if ($post->thumbnail)
    <div
        x-show="featuredPreviewOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/85 p-4 backdrop-blur-md"
        style="display: none;"
        @click.self="closeFeaturedPreview()">

        <div class="relative max-h-[85vh] max-w-5xl overflow-hidden rounded-[28px] border border-white/20 bg-white p-2 shadow-2xl">
            <button
                type="button"
                @click="closeFeaturedPreview()"
                class="absolute right-4 top-4 z-[110] inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-lg hover:bg-white"
                aria-label="Close preview">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <img
                src="{{ asset('storage/' . $post->thumbnail) }}"
                alt="{{ $post->title }}"
                class="max-h-[80vh] w-full rounded-[22px] object-contain">
        </div>
    </div>
    @endif

    {{-- Lightbox Modal --}}
    <div
        x-show="lightboxOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] flex flex-col justify-between bg-slate-950/90 p-4 backdrop-blur-md"
        style="display: none;"
        @click.self="closeLightbox()">

        <div class="flex items-center justify-between px-2 pt-2 text-white">
            <div class="inline-flex items-center rounded-full bg-white/10 px-3.5 py-1 text-xs font-bold backdrop-blur-md">
                <span x-text="lightboxIndex + 1"></span>
                <span class="mx-1 text-slate-400">/</span>
                <span x-text="additionalImages.length"></span>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    @click="zoomIn()"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
                    title="Zoom In">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                    </svg>
                </button>

                <button
                    type="button"
                    @click="zoomOut()"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
                    title="Zoom Out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                    </svg>
                </button>

                <button
                    type="button"
                    @click="resetZoom()"
                    class="inline-flex h-9 px-3 items-center justify-center rounded-full bg-white/10 text-xs font-semibold text-white transition hover:bg-white/20"
                    title="Reset Zoom">
                    Reset
                </button>

                <button
                    type="button"
                    @click="closeLightbox()"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/20 text-white transition hover:bg-white/30"
                    aria-label="Close Lightbox">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="relative flex flex-1 items-center justify-center overflow-hidden py-4">
            <template x-if="additionalImages.length > 1">
                <button
                    type="button"
                    @click="previousLightbox()"
                    class="absolute left-3 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white shadow-lg backdrop-blur-md transition hover:bg-white/20 sm:left-6"
                    aria-label="Previous">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </template>

            <img
                :src="additionalImages[lightboxIndex]?.url"
                :alt="additionalImages[lightboxIndex]?.caption || '{{ $post->title }}'"
                :style="'transform: scale(' + zoomLevel + '); transition: transform 0.2s ease-out;'"
                class="max-h-[75vh] max-w-full rounded-2xl object-contain select-none shadow-2xl">

            <template x-if="additionalImages.length > 1">
                <button
                    type="button"
                    @click="nextLightbox()"
                    class="absolute right-3 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white shadow-lg backdrop-blur-md transition hover:bg-white/20 sm:right-6"
                    aria-label="Next">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </template>
        </div>

        <div class="pb-3 text-center">
            <p
                x-show="additionalImages[lightboxIndex]?.caption"
                x-text="additionalImages[lightboxIndex]?.caption"
                class="mx-auto max-w-2xl text-sm font-medium text-slate-200">
            </p>
        </div>
    </div>
</div>
@endsection