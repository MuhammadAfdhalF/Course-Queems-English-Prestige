@extends('layouts.public')

@section('content')
<section class="bg-slate-50/70 py-10 sm:py-12 lg:py-14">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-6">
        {{-- Back Link & Header --}}
        <div>
            <a href="{{ route('courses.show', $courseLevel) }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 transition hover:text-[#080D4D]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to {{ $courseLevel->name }}
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200/90 bg-white p-6 sm:p-8 shadow-2xs">
            <div class="flex flex-wrap items-center gap-2.5">
                <span class="inline-flex rounded-md bg-emerald-50 border border-emerald-200/80 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700">
                    Public Preview
                </span>

                <span class="text-xs font-bold uppercase tracking-wider text-[#AD6B10]">
                    {{ $courseLevel->courseProgram?->name ?? 'Course Program' }} • {{ $courseLevel->name }}
                </span>
            </div>

            <h1 class="mt-3 text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                {{ $module->title }}
            </h1>

            @if ($module->short_description)
            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                {{ $module->short_description }}
            </p>
            @endif
        </div>

        {{-- Reading Canvas --}}
        <article class="rounded-2xl border border-slate-200/90 bg-white p-6 sm:p-8 lg:p-10 shadow-2xs">
            @forelse ($readableMaterials as $material)
            @php
            $type = strtolower($material->material_type ?? 'text');
            $title = trim($material->title ?? '');

            $isText = in_array($type, ['text', 'rich_text', 'content'], true);
            $isImage = in_array($type, ['image', 'thumbnail', 'photo', 'picture'], true);

            $genericTitles = ['text', 'content', 'rich text', 'rich_text', 'image', 'photo', 'picture'];
            $shouldShowTitle = $title && ! in_array(strtolower($title), $genericTitles, true);
            @endphp

            <div class="{{ $loop->first ? '' : 'mt-8 border-t border-slate-100 pt-8' }}">
                @if ($shouldShowTitle)
                <h2 class="mb-3 text-lg font-bold text-slate-900">
                    {{ $title }}
                </h2>
                @endif

                @if ($isImage && $material->file_path)
                <img
                    src="{{ asset('storage/' . $material->file_path) }}"
                    alt="{{ $title ?: $module->title }}"
                    class="w-full rounded-xl border border-slate-200 object-cover shadow-2xs">

                @if ($material->content)
                <div class="rich-text-content mt-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
                    {!! $material->content !!}
                </div>
                @endif

                @elseif ($isText)
                <div class="rich-text-content text-xs sm:text-sm text-slate-700 leading-relaxed">
                    {!! $material->content ?: '<p>Material content will be available soon.</p>' !!}
                </div>
                @endif
            </div>
            @empty
            <div class="py-10 text-center">
                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>

                <h3 class="mt-3 text-sm font-bold text-slate-900">
                    Preview content for this module is being prepared.
                </h3>

                <p class="mt-1 text-xs font-medium text-slate-500">
                    Check back soon or enroll to access the full course syllabus.
                </p>
            </div>
            @endforelse

            @if ($hasExcludedMedia)
            <div class="mt-8 rounded-xl border border-slate-200/80 bg-slate-50/80 p-4 text-xs font-medium text-slate-500">
                <div class="flex items-center gap-2 text-slate-700 font-bold mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#AD6B10]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Additional Media Available</span>
                </div>
                Additional media (video, audio, or downloadable files) is available after enrolling in this course.
            </div>
            @endif
        </article>

        {{-- Bottom Conversion CTA Banner --}}
        <div class="rounded-2xl border border-slate-200/90 border-t-2 border-t-[#AD6B10] bg-white p-6 sm:p-8 shadow-2xs">
            @if ($hasActiveEnrollment && $activeEnrollment)
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">
                        You are enrolled in this course!
                    </h3>
                    <p class="mt-1 text-xs font-medium text-slate-500">
                        Continue your learning path to complete practices, final exams, and earn your certificate.
                    </p>
                </div>

                <a
                    href="{{ route('student.learning-path', $activeEnrollment) }}"
                    class="inline-flex h-9 items-center justify-center rounded-xl bg-[#080D4D] px-5 text-xs font-bold text-white shadow-2xs transition hover:bg-[#060A3B]">
                    Continue Learning
                </a>
            </div>
            @elseif (auth()->check())
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">
                        Ready to start learning?
                    </h3>
                    <p class="mt-1 text-xs font-medium text-slate-500">
                        Enroll now to get unlimited access to all modules, interactive practices, and official certificates.
                    </p>
                </div>

                <a
                    href="{{ route('courses.order.create', $courseLevel) }}"
                    class="inline-flex h-9 items-center justify-center rounded-xl bg-[#080D4D] px-5 text-xs font-bold text-white shadow-2xs transition hover:bg-[#060A3B]">
                    Enroll Course Now
                </a>
            </div>
            @else
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">
                        Enjoyed this preview?
                    </h3>
                    <p class="mt-1 text-xs font-medium text-slate-500">
                        Log in or create an account to enroll and unlock all lessons, practices, and certificates.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex h-9 items-center justify-center rounded-xl bg-[#080D4D] px-4 text-xs font-bold text-white shadow-2xs transition hover:bg-[#060A3B]">
                        Login to Enroll
                    </a>

                    <a
                        href="{{ route('courses.show', $courseLevel) }}"
                        class="inline-flex h-9 items-center justify-center rounded-xl border border-slate-200/90 bg-white px-4 text-xs font-bold text-slate-700 shadow-2xs transition hover:bg-slate-50">
                        View Course Details
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
