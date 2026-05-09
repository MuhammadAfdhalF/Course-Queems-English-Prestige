@extends('layouts.admin', [
'pageTitle' => 'Edit Final Exam Question',
'pageSubtitle' => $finalExamQuestion->finalExam->title,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.final-exams.questions.index', $finalExamQuestion->finalExam)"
        back-label="Back to Questions" />

    <x-admin.flash-message />

    @include('partials.admin.course-management.final-exam-questions.form', [
    'finalExam' => $finalExamQuestion->finalExam,
    'question' => $finalExamQuestion,
    'action' => route('admin.course-management.final-exam-questions.update', $finalExamQuestion),
    'method' => 'PUT',
    'submitLabel' => 'Save Changes',
    'nextSortOrder' => null,
    ])
</section>
@endsection

@push('scripts')
@vite(['resources/js/admin-rich-text.js'])
@endpush