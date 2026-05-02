@extends('layouts.admin', [
'pageTitle' => 'Home Page',
'pageSubtitle' => 'Website CMS',
])

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-header
        title="Home Page CMS"
        description="Manage content displayed on the website home page.">
    </x-admin.page-header>

    <div class="grid gap-6 md:grid-cols-3">
        <a
            href="{{ route('admin.cms.hero-sections.index') }}"
            class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                Section
            </p>

            <h3 class="mt-3 text-2xl font-bold text-slate-900">
                Hero Sections
            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Manage the main banner displayed on the home page.
            </p>

            <p class="mt-5 text-3xl font-bold text-[var(--color-brand-blue)]">
                {{ $heroSectionsCount }}
            </p>
        </a>

        <a
            href="{{ route('admin.cms.faqs.index') }}"
            class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                Section
            </p>

            <h3 class="mt-3 text-2xl font-bold text-slate-900">
                FAQ
            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Manage frequently asked questions displayed on the website.
            </p>

            <p class="mt-5 text-3xl font-bold text-[var(--color-brand-blue)]">
                {{ $faqsCount }}
            </p>
        </a>

        <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                Section
            </p>

            <h3 class="mt-3 text-2xl font-bold text-slate-900">
                Featured Testimonials
            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Manage selected testimonials displayed on the home page.
            </p>

            <p class="mt-5 text-3xl font-bold text-[var(--color-brand-blue)]">
                {{ $featuredTestimonialsCount }}
            </p>
        </div>
    </div>
</section>
@endsection