@extends('layouts.admin', [
'pageTitle' => 'Create Final Exam',
'pageSubtitle' => $courseLevel->name,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.levels.final-exam.index', $courseLevel)"
        back-label="Back to Final Exam" />

    <x-admin.flash-message />

    @include('partials.admin.course-management.final-exams.form', [
    'courseLevel' => $courseLevel,
    'finalExam' => null,
    'action' => route('admin.course-management.levels.final-exam.store', $courseLevel),
    'method' => 'POST',
    'submitLabel' => 'Save Final Exam',
    ])
</section>
@endsection

@push('scripts')
@vite(['resources/js/admin-rich-text.js'])
@endpush