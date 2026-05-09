@extends('layouts.public')

@section('content')
@php
$thumbnailUrl = $post->thumbnail
? asset('storage/' . $post->thumbnail)
: 'https://placehold.co/1200x700/e8eef8/1e293b?text=Queens+News';

$eventDate = $post->event_date
? $post->event_date->format('M d, Y')
: null;

$galleryItems = $post->images
->map(function ($image) {
return [
'url' => asset('storage/' . $image->image),
'caption' => $image->caption,
];
})
->values();
@endphp

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
        <div class="reveal mx-auto max-w-6xl">
            <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white p-3 shadow-sm">
                <img
                    src="{{ $thumbnailUrl }}"
                    alt="{{ $post->title }}"
                    class="aspect-[16/7] w-full rounded-[22px] object-cover md:aspect-[16/6]">
            </div>
        </div>

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

        @if ($post->images->isNotEmpty())
        <div
            x-data="{
                    open: false,
                    activeIndex: 0,
                    images: @js($galleryItems),

                    openPreview(index) {
                        this.activeIndex = index;
                        this.open = true;
                    },

                    closePreview() {
                        this.open = false;
                    },

                    next() {
                        this.activeIndex = (this.activeIndex + 1) % this.images.length;
                    },

                    previous() {
                        this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
                    }
                }"
            @keydown.escape.window="closePreview()"
            class="reveal reveal-delay-2 mx-auto mt-14 max-w-6xl">
            <div class="border-t border-slate-200 pt-10">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-brand-gold)]">
                            Gallery
                        </p>

                        <h2 class="mt-3 text-2xl font-bold text-slate-900">
                            More Photos
                        </h2>
                    </div>

                    <p class="text-sm text-slate-500">
                        Click any image to preview.
                    </p>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($post->images as $image)
                    <button
                        type="button"
                        @click="openPreview({{ $loop->index }})"
                        class="group overflow-hidden rounded-[22px] border border-slate-200 bg-white p-3 text-left shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="relative overflow-hidden rounded-[16px] bg-slate-100">
                            <img
                                src="{{ asset('storage/' . $image->image) }}"
                                alt="{{ $image->caption ?: $post->title }}"
                                class="aspect-[4/3] w-full object-cover transition duration-300 group-hover:scale-105">

                            <div class="absolute inset-0 flex items-center justify-center bg-slate-950/0 transition group-hover:bg-slate-950/30">
                                <span class="scale-90 rounded-full bg-white/95 px-4 py-2 text-xs font-bold text-[var(--color-brand-blue)] opacity-0 shadow-sm transition group-hover:scale-100 group-hover:opacity-100">
                                    Preview
                                </span>
                            </div>
                        </div>

                        @if ($image->caption)
                        <p class="px-1 pt-3 text-center text-sm font-medium leading-6 text-slate-500">
                            {{ $image->caption }}
                        </p>
                        @endif
                    </button>
                    @endforeach
                </div>
            </div>

            <div
                x-show="open"
                x-cloak
                x-transition.opacity
                class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/55 px-4 py-6"
                @click.self="closePreview()">
                <button
                    type="button"
                    @click.prevent="closePreview()"
                    class="fixed right-4 top-4 z-[110] flex h-11 w-11 items-center justify-center rounded-full bg-white/95 text-slate-900 shadow-sm transition hover:bg-white"
                    aria-label="Close preview">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="relative flex min-h-[80vh] w-full max-w-6xl flex-col items-center justify-center">
                    <div class="flex w-full items-center justify-center px-12 sm:px-16">
                        <img
                            :src="images[activeIndex]?.url"
                            :alt="images[activeIndex]?.caption || 'Gallery preview'"
                            class="max-h-[70vh] max-w-full rounded-[22px] object-contain shadow-2xl">
                    </div>

                    <div class="mx-auto mt-5 max-w-3xl text-center text-white">
                        <p
                            x-show="images[activeIndex]?.caption"
                            x-text="images[activeIndex]?.caption"
                            class="text-center text-sm leading-7 text-white">
                        </p>

                        <p class="mt-2 text-center text-xs font-semibold uppercase tracking-[0.16em] text-white/70">
                            <span x-text="activeIndex + 1"></span> / <span x-text="images.length"></span>
                        </p>
                    </div>

                    <div
                        x-show="images.length > 1"
                        class="pointer-events-none absolute inset-y-0 left-0 right-0 flex items-center justify-between">
                        <button
                            type="button"
                            @click.stop="previous()"
                            class="pointer-events-auto flex h-11 w-11 items-center justify-center rounded-full bg-white/95 text-slate-900 shadow-sm transition hover:bg-white"
                            aria-label="Previous image">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <button
                            type="button"
                            @click.stop="next()"
                            class="pointer-events-auto flex h-11 w-11 items-center justify-center rounded-full bg-white/95 text-slate-900 shadow-sm transition hover:bg-white"
                            aria-label="Next image">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
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
@endsection