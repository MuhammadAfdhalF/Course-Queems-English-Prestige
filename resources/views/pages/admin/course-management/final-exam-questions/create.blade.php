@extends('layouts.admin', [
'pageTitle' => 'Add Final Exam Question',
'pageSubtitle' => $finalExam->title,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.final-exams.questions.index', $finalExam)"
        back-label="Back to Questions" />

    <x-admin.flash-message />

    @include('partials.admin.course-management.final-exam-questions.form', [
    'finalExam' => $finalExam,
    'question' => null,
    'action' => route('admin.course-management.final-exams.questions.store', $finalExam),
    'method' => 'POST',
    'submitLabel' => 'Save Question',
    'nextSortOrder' => $nextSortOrder,
    'activateWhenReady' => $activateWhenReady ?? false,
    ])
</section>
@endsection

@push('scripts')
@vite(['resources/js/admin-rich-text.js'])
@endpush