@extends('layouts.admin', [
'pageTitle' => 'Preview Final Exam',
'pageSubtitle' => $finalExam->title,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.final-exams.questions.index', $finalExam)"
        back-label="Back to Questions">
        <x-slot:actions>
            <a
                href="{{ route('admin.course-management.final-exams.questions.create', $finalExam) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                <x-admin.icon name="plus" class="h-5 w-5" />
                <span>Add Question</span>
            </a>
        </x-slot:actions>
    </x-admin.page-toolbar>

    <x-admin.table-card class="p-6">
        <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-start">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Final Exam Preview
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

                @if ($finalExam->is_active)
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                    Active
                </span>
                @else
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                    Inactive
                </span>
                @endif
            </div>
        </div>

        @if ($finalExam->description)
        <div class="rich-text-content mt-6 border-t border-slate-200 pt-6">
            {!! $finalExam->description !!}
        </div>
        @endif

        <div class="mt-6 grid gap-3 sm:grid-cols-3">
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                    Passing Grade
                </p>
                <p class="mt-2 text-xl font-bold text-slate-900">
                    {{ $finalExam->passing_grade }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                    Max Attempts
                </p>
                <p class="mt-2 text-xl font-bold text-slate-900">
                    {{ $finalExam->max_attempts ?? 'Unlimited' }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                    Status
                </p>
                <p class="mt-2 text-xl font-bold text-slate-900">
                    {{ $finalExam->is_active ? 'Active' : 'Inactive' }}
                </p>
            </div>
        </div>
    </x-admin.table-card>

    @if ($questions->isEmpty())
    <x-admin.table-card class="p-10 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
            <x-admin.icon name="check" class="h-6 w-6" />
        </div>

        <h3 class="mt-4 text-lg font-bold text-slate-900">
            No questions to preview
        </h3>

        <p class="mt-2 text-sm text-slate-500">
            Add questions first to preview this final exam.
        </p>

        <a
            href="{{ route('admin.course-management.final-exams.questions.create', $finalExam) }}"
            class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Add Question</span>
        </a>
    </x-admin.table-card>
    @else
    <article class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-10">
        <div class="space-y-10">
            @foreach ($questions as $question)
            @include('partials.admin.course-management.final-exam-questions.preview-question', [
            'question' => $question,
            'loopIndex' => $loop->iteration,
            ])
            @endforeach
        </div>
    </article>
    @endif
</section>
@endsection