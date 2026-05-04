@extends('layouts.admin', [
'pageTitle' => 'Add Course Level',
'pageSubtitle' => $courseProgram->name,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.programs.levels.index', $courseProgram)"
        back-label="Back to Course Levels" />

    <x-admin.flash-message />

    @include('partials.admin.course-management.levels.form', [
    'courseProgram' => $courseProgram,
    'courseLevel' => null,
    'action' => route('admin.course-management.programs.levels.store', $courseProgram),
    'method' => 'POST',
    'submitLabel' => 'Save Course Level',
    'nextSortOrder' => $nextSortOrder,
    ])
</section>
@endsection

@push('scripts')
@vite(['resources/js/admin-rich-text.js'])
@endpush