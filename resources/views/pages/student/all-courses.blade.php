@extends('layouts.student')

@section('content')
@php
$courseItems = [
[
'title' => 'Academic Writing',
'mode' => 'Online',
'level' => 'Advanced',
'price' => 'Rp. 100.000',
'description' => 'Refine your ability to construct complex arguments and professional research papers...',
'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'IELTS Breakthrough',
'mode' => 'Offline',
'level' => 'Intermediate',
'price' => 'Rp. 100.000',
'description' => 'Strategic preparation focused on achieving Band 8+ through intensive practice and expe...',
'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'Executive',
'mode' => 'Offline',
'level' => 'Advanced',
'price' => 'Rp. 100.000',
'description' => 'Master the nuances of professional English for boardrooms, high-stakes negotiations, and...',
'image' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'IELTS Breakthrough',
'mode' => 'Offline',
'level' => 'Intermediate',
'price' => 'Rp. 100.000',
'description' => 'Strategic preparation focused on achieving Band 8+ through intensive practice and expe...',
'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'Speaking Confidence',
'mode' => 'Offline',
'level' => 'Beginner',
'price' => 'Rp. 100.000',
'description' => 'Break language barriers and gain the courage to speak fluently in any social or business...',
'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'Grammar Essentials',
'mode' => 'Online',
'level' => 'Intermediate',
'price' => 'Rp. 100.000',
'description' => 'Deep dive into the structural foundations of English for precise and sophisticated languag...',
'image' => 'https://images.unsplash.com/photo-1519682337058-a94d519337bc?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'Public Speaking Pro',
'mode' => 'Online',
'level' => 'Advanced',
'price' => 'Rp. 100.000',
'description' => 'Persuade and inspire. Techniques for impactful presentations, storytelling, and charismatic...',
'image' => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'Grammar Essentials',
'mode' => 'Online',
'level' => 'Intermediate',
'price' => 'Rp. 100.000',
'description' => 'Deep dive into the structural foundations of English for precise and sophisticated languag...',
'image' => 'https://images.unsplash.com/photo-1519682337058-a94d519337bc?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
];
@endphp

<section class="-mx-4 -mt-6 overflow-hidden bg-white lg:-mx-8">
    {{-- HERO --}}
    <div class="relative overflow-hidden border-b border-slate-200 bg-[#F7FAFD]">
        <div class="absolute inset-0">
            <img
                src="{{ asset('images/course-hero-bg.png') }}"
                alt="Course Hero Background"
                class="h-full w-full object-cover"
                onerror="this.style.display='none';">
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-16 text-center lg:px-8 lg:py-20">
            <div class="reveal mx-auto flex h-16 w-16 items-center justify-center rounded-full border border-[var(--color-brand-blue)] bg-white shadow-sm">
                <div class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 text-[var(--color-brand-blue)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v11.494m-5.747-8.62h11.494" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 5.25h15A2.25 2.25 0 0121.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9A2.25 2.25 0 014.5 5.25z" />
                    </svg>
                </div>

                <span class="absolute mt-[-42px] ml-[44px] flex h-5 w-5 items-center justify-center rounded-full bg-[var(--color-brand-gold)] text-[10px] font-bold text-white">
                    ★
                </span>
            </div>

            <h1 class="reveal reveal-delay-1 mt-6 text-4xl font-bold leading-tight text-[var(--color-brand-blue)] md:text-5xl">
                Our <span class="text-[var(--color-brand-gold)]">Programs</span>
            </h1>

            <p class="reveal reveal-delay-2 mx-auto mt-4 max-w-2xl text-base leading-8 text-slate-500">
                A curated collection of premium English courses designed for high-achieving
                professionals and students seeking global excellence.
            </p>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="relative z-10 -mt-8">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="reveal reveal-delay-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="grid gap-4 lg:grid-cols-[1fr_1fr_1fr_auto] lg:items-end">
                    <div>
                        <label class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                            Type Course
                        </label>

                        <x-ui.select>
                            <option>All Type Course</option>
                            <option>Online</option>
                            <option>Offline</option>
                        </x-ui.select>
                    </div>

                    <div>
                        <label class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                            Categories Course
                        </label>

                        <x-ui.select>
                            <option>All Categories</option>
                            <option>Academic</option>
                            <option>Business</option>
                            <option>Grammar</option>
                            <option>Speaking</option>
                        </x-ui.select>
                    </div>

                    <div>
                        <label class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                            Level Course
                        </label>

                        <x-ui.select>
                            <option>All Levels</option>
                            <option>Beginner</option>
                            <option>Intermediate</option>
                            <option>Advanced</option>
                        </x-ui.select>
                    </div>

                    <div>
                        <x-ui.button class="w-full px-6 py-3 lg:w-auto">
                            Filter Courses
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- GRID --}}
    <div class="mx-auto max-w-7xl px-4 py-12 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($courseItems as $index => $course)
            @php
            $delayClass = match ($index % 4) {
            1 => 'reveal-delay-1',
            2 => 'reveal-delay-2',
            3 => 'reveal-delay-3',
            default => '',
            };
            @endphp

            <div class="reveal {{ $delayClass }}">
                <x-ui.course-card
                    :title="$course['title']"
                    :mode="$course['mode']"
                    :level="$course['level']"
                    :price="$course['price']"
                    :description="$course['description']"
                    :image="$course['image']"
                    :href="$course['href']" />
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection