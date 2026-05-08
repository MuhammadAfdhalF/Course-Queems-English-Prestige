@extends('layouts.admin', [
'pageTitle' => 'Edit Final Exam',
'pageSubtitle' => $finalExam->courseLevel->name,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.levels.final-exam.index', $finalExam->courseLevel)"
        back-label="Back to Final Exam" />

    <x-admin.flash-message />

    @include('partials.admin.course-management.final-exams.form', [
    'courseLevel' => $finalExam->courseLevel,
    'finalExam' => $finalExam,
    'action' => route('admin.course-management.final-exams.update', $finalExam),
    'method' => 'PUT',
    'submitLabel' => 'Save Changes',
    ])
</section>
@endsection

@push('scripts')
@vite(['resources/js/admin-rich-text.js'])
@endpush