@extends('layouts.admin', [
'pageTitle' => 'Add Practice Question',
'pageSubtitle' => $modulePractice->title,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.practices.questions.index', $modulePractice)"
        back-label="Back to Questions" />

    <x-admin.flash-message />

    @include('partials.admin.course-management.practice-questions.form', [
    'practice' => $modulePractice,
    'question' => null,
    'action' => route('admin.course-management.practices.questions.store', $modulePractice),
    'method' => 'POST',
    'submitLabel' => 'Save Question',
    'nextSortOrder' => $nextSortOrder,
    ])
</section>
@endsection

@push('scripts')
@vite(['resources/js/admin-rich-text.js'])
@endpush