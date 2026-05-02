@extends('layouts.admin', [
'pageTitle' => 'About Page',
'pageSubtitle' => 'Website CMS',
])

@section('content')
@php
$sections = [
[
'title' => 'About Us',
'description' => 'Manage the main profile and introduction of Queens English Prestige.',
'count' => $aboutUsCount,
'href' => null,
'status' => 'Coming Soon',
],
[
'title' => 'Profile Videos',
'description' => 'Manage profile videos and thumbnails displayed on the about page.',
'count' => $profileVideosCount,
'href' => null,
'status' => 'Coming Soon',
],
[
'title' => 'Why Choose Us',
'description' => 'Manage reasons and advantages displayed on the about page.',
'count' => $whyChooseUsCount,
'href' => route('admin.cms.why-choose-us.index'),
'status' => 'Manage',
],
[
'title' => 'Vision & Mission',
'description' => 'Manage the vision and mission statements of the institution.',
'count' => $visionsMissionsCount,
'href' => route('admin.cms.vision-mission.index'),
'status' => 'Manage',
],
[
'title' => 'Mentors',
'description' => 'Manage mentor profiles, photos, positions, and expertise.',
'count' => $mentorsCount,
'href' => null,
'status' => 'Coming Soon',
],
];
@endphp

<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-header
        title="About Page CMS"
        description="Manage content displayed on the website about page.">
    </x-admin.page-header>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($sections as $section)
        @if ($section['href'])
        <a
            href="{{ $section['href'] }}"
            class="group rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                    Section
                </p>

                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                    {{ $section['status'] }}
                </span>
            </div>

            <h3 class="mt-4 text-2xl font-bold text-slate-900 transition group-hover:text-[var(--color-brand-blue)]">
                {{ $section['title'] }}
            </h3>

            <p class="mt-2 min-h-[48px] text-sm leading-6 text-slate-500">
                {{ $section['description'] }}
            </p>

            <div class="mt-6 flex items-end justify-between">
                <p class="text-3xl font-bold text-[var(--color-brand-blue)]">
                    {{ $section['count'] }}
                </p>

                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition group-hover:bg-[var(--color-brand-blue)] group-hover:text-white">
                    <x-admin.icon name="eye" class="h-4 w-4" />
                </span>
            </div>
        </a>
        @else
        <div class="rounded-[24px] border border-slate-200 bg-white p-6 opacity-80 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                    Section
                </p>

                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                    {{ $section['status'] }}
                </span>
            </div>

            <h3 class="mt-4 text-2xl font-bold text-slate-900">
                {{ $section['title'] }}
            </h3>

            <p class="mt-2 min-h-[48px] text-sm leading-6 text-slate-500">
                {{ $section['description'] }}
            </p>

            <div class="mt-6 flex items-end justify-between">
                <p class="text-3xl font-bold text-[var(--color-brand-blue)]">
                    {{ $section['count'] }}
                </p>

                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                    <x-admin.icon name="image" class="h-4 w-4" />
                </span>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</section>
@endsection