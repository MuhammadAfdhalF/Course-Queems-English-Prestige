@extends('layouts.admin', [
'pageTitle' => 'Edit Practice Question',
'pageSubtitle' => $modulePracticeQuestion->practice->title,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.practices.questions.index', $modulePracticeQuestion->practice)"
        back-label="Back to Questions" />

    <x-admin.flash-message />

    @include('partials.admin.course-management.practice-questions.form', [
    'practice' => $modulePracticeQuestion->practice,
    'question' => $modulePracticeQuestion,
    'action' => route('admin.course-management.practice-questions.update', $modulePracticeQuestion),
    'method' => 'PUT',
    'submitLabel' => 'Save Changes',
    'nextSortOrder' => null,
    ])
</section>
@endsection

@push('scripts')
@vite(['resources/js/admin-rich-text.js'])
@endpush