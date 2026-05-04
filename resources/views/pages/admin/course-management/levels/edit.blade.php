@extends('layouts.admin', [
'pageTitle' => 'Edit Course Level',
'pageSubtitle' => $courseLevel->courseProgram->name,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.programs.levels.index', $courseLevel->courseProgram)"
        back-label="Back to Course Levels" />

    <x-admin.flash-message />

    @include('partials.admin.course-management.levels.form', [
    'courseProgram' => $courseLevel->courseProgram,
    'courseLevel' => $courseLevel,
    'action' => route('admin.course-management.levels.update', $courseLevel),
    'method' => 'PUT',
    'submitLabel' => 'Save Changes',
    'nextSortOrder' => null,
    ])
</section>
@endsection

@push('scripts')
@vite(['resources/js/admin-rich-text.js'])
@endpush