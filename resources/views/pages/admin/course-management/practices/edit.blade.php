@extends('layouts.admin', [
'pageTitle' => 'Edit Module Practice',
'pageSubtitle' => $modulePractice->module->title,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.modules.practice.index', $modulePractice->module)"
        back-label="Back to Practice" />

    <x-admin.flash-message />

    @include('partials.admin.course-management.practices.form', [
    'module' => $modulePractice->module,
    'practice' => $modulePractice,
    'action' => route('admin.course-management.practices.update', $modulePractice),
    'method' => 'PUT',
    'submitLabel' => 'Save Changes',
    ])
</section>
@endsection

@push('scripts')
@vite(['resources/js/admin-rich-text.js'])
@endpush