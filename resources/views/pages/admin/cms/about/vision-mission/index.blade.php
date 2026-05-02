@extends('layouts.admin', [
'pageTitle' => 'Vision & Mission',
'pageSubtitle' => 'About Page CMS',
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.cms.about.index')"
        back-label="Back to About Page" />

    <x-admin.flash-message />

    @include('partials.admin.cms.about.vision-mission.form')
</section>
@endsection