@extends('layouts.admin', [
'pageTitle' => 'Final Exam Questions',
'pageSubtitle' => $finalExam->title,
])

@section('content')
<section
    x-data="{
        deleteModalOpen: false,
        selectedItem: {
            title: '',
            delete_url: '#'
        },
        openDeleteModal(item) {
            this.selectedItem = item;
            this.deleteModalOpen = true;
        }
    }"
    class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.levels.final-exam.index', $finalExam->courseLevel)"
        back-label="Back to Final Exam">
        <x-slot:actions>
            <a
                href="{{ route('admin.course-management.final-exams.preview', $finalExam) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <x-admin.icon name="eye" class="h-5 w-5" />
                <span>Preview Final Exam</span>
            </a>

            <a
                href="{{ route('admin.course-management.final-exams.questions.create', $finalExam) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                <x-admin.icon name="plus" class="h-5 w-5" />
                <span>Add Question</span>
            </a>
        </x-slot:actions>
    </x-admin.page-toolbar>

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Final Exam
                </p>

                <h2 class="mt-2 text-2xl font-bold text-slate-900">
                    {{ $finalExam->title }}
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    {{ $finalExam->courseLevel->courseProgram->name }}
                    —
                    {{ $finalExam->courseLevel->name }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2 md:justify-end">
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                    {{ $questions->count() }} questions
                </span>

                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                    {{ ucfirst($finalExam->grading_method) }}
                </span>
            </div>
        </div>
    </x-admin.table-card>

    @include('partials.admin.course-management.final-exam-questions.table')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Question"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteFinalExamQuestionForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this question?" />
</section>
@endsection