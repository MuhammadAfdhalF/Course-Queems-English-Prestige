@extends('layouts.admin', [
'pageTitle' => 'Vision & Mission',
'pageSubtitle' => 'About Page CMS',
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-header
        title="Vision & Mission"
        description="Manage the vision and mission statements displayed on the About page.">
        <x-slot:actions>
            <a
                href="{{ route('admin.cms.about.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <x-admin.icon name="arrow-left" class="h-4 w-4" />
                <span>Back to About Page</span>
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.flash-message />

    @include('partials.admin.cms.about.vision-mission.form')
</section>
@endsection