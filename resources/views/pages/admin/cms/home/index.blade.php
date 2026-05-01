@extends('layouts.admin', [
    'pageTitle' => 'Home Page',
    'pageSubtitle' => 'Website CMS',
])

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-header
        title="Home Page CMS"
        description="Kelola konten yang tampil di halaman beranda website.">
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
                Kelola banner utama halaman beranda.
            </p>
            <p class="mt-5 text-3xl font-bold text-[var(--color-brand-blue)]">
                {{ $heroSectionsCount }}
            </p>
        </a>

        <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                Section
            </p>
            <h3 class="mt-3 text-2xl font-bold text-slate-900">
                FAQ
            </h3>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                Kelola pertanyaan yang sering ditanyakan.
            </p>
            <p class="mt-5 text-3xl font-bold text-[var(--color-brand-blue)]">
                {{ $faqsCount }}
            </p>
        </div>

        <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                Section
            </p>
            <h3 class="mt-3 text-2xl font-bold text-slate-900">
                Featured Testimonials
            </h3>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                Testimoni pilihan untuk halaman beranda.
            </p>
            <p class="mt-5 text-3xl font-bold text-[var(--color-brand-blue)]">
                {{ $featuredTestimonialsCount }}
            </p>
        </div>
    </div>
</section>
@endsection