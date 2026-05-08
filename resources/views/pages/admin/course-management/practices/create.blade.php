@extends('layouts.admin', [
'pageTitle' => 'Create Module Practice',
'pageSubtitle' => $module->title,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.modules.practice.index', $module)"
        back-label="Back to Practice" />

    <x-admin.flash-message />

    @include('partials.admin.course-management.practices.form', [
    'module' => $module,
    'practice' => null,
    'action' => route('admin.course-management.modules.practice.store', $module),
    'method' => 'POST',
    'submitLabel' => 'Save Practice',
    ])
</section>
@endsection

@push('scripts')
@vite(['resources/js/admin-rich-text.js'])
@endpush