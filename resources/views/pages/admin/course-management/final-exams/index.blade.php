@extends('layouts.admin', [
'pageTitle' => 'Final Exam',
'pageSubtitle' => $courseLevel->name,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.programs.levels.index', $courseLevel->courseProgram)"
        back-label="Back to Course Levels" />

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Course Level
                </p>

                <h2 class="mt-2 text-2xl font-bold text-slate-900">
                    {{ $courseLevel->name }}
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    {{ $courseLevel->courseProgram->name }}
                </p>

                @if ($courseLevel->short_description)
                <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
                    {{ $courseLevel->short_description }}
                </p>
                @endif
            </div>

            <div class="flex flex-wrap gap-2 md:justify-end">
                @if ($courseLevel->is_active)
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                    Active Level
                </span>
                @else
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                    Inactive Level
                </span>
                @endif
            </div>
        </div>
    </x-admin.table-card>

    @if (! $finalExam)
    <x-admin.table-card class="p-10 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-700">
            <x-admin.icon name="check" class="h-6 w-6" />
        </div>

        <h3 class="mt-4 text-lg font-bold text-slate-900">
            No final exam yet
        </h3>

        <p class="mx-auto mt-2 max-w-xl text-sm leading-6 text-slate-500">
            This course level does not have a final exam yet. Create one final exam to evaluate students before they complete this level.
        </p>

        <a
            href="{{ route('admin.course-management.levels.final-exam.create', $courseLevel) }}"
            class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Create Final Exam</span>
        </a>
    </x-admin.table-card>
    @else
    <x-admin.table-card class="p-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                        {{ ucfirst($finalExam->grading_method) }}
                    </span>

                    @if ($finalExam->is_active)
                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                        Active
                    </span>
                    @else
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                        Inactive
                    </span>
                    @endif
                </div>

                <h3 class="mt-4 text-2xl font-bold text-slate-900">
                    {{ $finalExam->title }}
                </h3>

                @if ($finalExam->description)
                <div class="rich-text-content mt-5 max-w-none">
                    {!! $finalExam->description !!}
                </div>
                @else
                <p class="mt-4 text-sm text-slate-500">
                    No final exam instruction.
                </p>
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
                            Questions
                        </p>
                        <p class="mt-2 text-xl font-bold text-slate-900">
                            {{ $finalExam->questions_count }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex shrink-0 flex-col gap-3 sm:flex-row lg:flex-col">
                <div class="flex shrink-0 flex-col gap-3 sm:flex-row lg:flex-col">
                    <a
                        href="{{ route('admin.course-management.final-exams.edit', $finalExam) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <x-admin.icon name="pencil" class="h-4 w-4" />
                        <span>Edit Final Exam</span>
                    </a>

                    <a
                        href="{{ route('admin.course-management.final-exams.preview', $finalExam) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <x-admin.icon name="eye" class="h-4 w-4" />
                        <span>Preview Final Exam</span>
                    </a>

                    <a
                        href="{{ route('admin.course-management.final-exams.questions.index', $finalExam) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                        <x-admin.icon name="check" class="h-4 w-4" />
                        <span>Manage Questions</span>
                    </a>
                </div>
            </div>
        </div>
    </x-admin.table-card>
    @endif
</section>
@endsection