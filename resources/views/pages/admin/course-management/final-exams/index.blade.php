@extends('layouts.admin', [
'pageTitle' => 'Final Exam Sections',
'pageSubtitle' => $courseLevel->name,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.programs.levels.index', $courseLevel->courseProgram)"
        back-label="Back to Course Levels" />

    @php
        $builderUrl = route('admin.course-management.programs.builder', [
            'courseProgram' => $courseLevel->course_program_id,
            'level' => $courseLevel->id,
            'tab' => 'final-exam'
        ]);
    @endphp

    @include('partials.admin.course-management.legacy-builder-banner', ['builderUrl' => $builderUrl])

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

            <div class="flex flex-wrap items-center gap-3 md:justify-end">
                @if ($courseLevel->is_active)
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                    Active Level
                </span>
                @else
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                    Inactive Level
                </span>
                @endif

                <a
                    href="{{ route('admin.course-management.levels.final-exam.create', $courseLevel) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                    <x-admin.icon name="plus" class="h-4 w-4" />
                    <span>Add Final Exam Section</span>
                </a>
            </div>
        </div>
    </x-admin.table-card>

    @if ($finalExams->isEmpty())
    <x-admin.table-card class="p-10 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-700">
            <x-admin.icon name="check" class="h-6 w-6" />
        </div>

        <h3 class="mt-4 text-lg font-bold text-slate-900">
            No Final Exam sections yet
        </h3>

        <p class="mx-auto mt-2 max-w-xl text-sm leading-6 text-slate-500">
            This course level does not have any Final Exam sections yet. Create section records (e.g., Listening, Structure, Reading) to evaluate students before completing this level.
        </p>

        <a
            href="{{ route('admin.course-management.levels.final-exam.create', $courseLevel) }}"
            class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Add Final Exam Section</span>
        </a>
    </x-admin.table-card>
    @else
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h3 class="text-lg font-bold text-slate-900">
                Final Exam Sections ({{ $finalExams->count() }})
            </h3>

            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                Active Final Exam Sections ({{ $finalExams->where('is_active', true)->count() }})
            </span>
        </div>

        @foreach ($finalExams as $section)
        <x-admin.table-card class="p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700">
                            Order: {{ $section->sort_order }}
                        </span>

                        <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                            {{ ucfirst($section->grading_method) }}
                        </span>

                        @if ($section->is_active)
                            @if ($section->active_questions_count > 0)
                            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                Active (Ready)
                            </span>
                            @else
                            <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700" title="Configuration Warning: No active questions">
                                Active (No Active Questions)
                            </span>
                            @endif
                        @else
                            @if ($section->active_questions_count > 0)
                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                Inactive (Ready to Activate)
                            </span>
                            @else
                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                                Inactive (Add Questions First)
                            </span>
                            @endif
                        @endif
                    </div>

                    <h3 class="mt-4 text-2xl font-bold text-slate-900">
                        {{ $section->title }}
                    </h3>

                    @if ($section->description)
                    <div class="rich-text-content mt-4 max-w-none">
                        {!! $section->description !!}
                    </div>
                    @else
                    <p class="mt-3 text-sm text-slate-500">
                        No section instruction.
                    </p>
                    @endif

                    <div class="mt-6 grid gap-3 sm:grid-cols-4">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                                Passing Grade
                            </p>
                            <p class="mt-2 text-xl font-bold text-slate-900">
                                {{ $section->passing_grade }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                                Max Attempts
                            </p>
                            <p class="mt-2 text-xl font-bold text-slate-900">
                                {{ $section->max_attempts ?? 'Unlimited' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                                Active Questions
                            </p>
                            <p class="mt-2 text-xl font-bold text-slate-900">
                                {{ $section->active_questions_count }} <span class="text-xs font-normal text-slate-500">/ {{ $section->questions_count }} total</span>
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                                Student Attempts
                            </p>
                            <p class="mt-2 text-xl font-bold text-slate-900">
                                {{ $section->attempts_count }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex shrink-0 flex-col gap-2.5 sm:flex-row lg:flex-col lg:w-48">
                    <a
                        href="{{ route('admin.course-management.final-exams.questions.index', $section) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:opacity-90">
                        <x-admin.icon name="check" class="h-4 w-4" />
                        <span>Manage Questions</span>
                    </a>

                    <a
                        href="{{ route('admin.course-management.final-exams.edit', $section) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <x-admin.icon name="pencil" class="h-4 w-4" />
                        <span>Edit Section</span>
                    </a>

                    <a
                        href="{{ route('admin.course-management.final-exams.preview', $section) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <x-admin.icon name="eye" class="h-4 w-4" />
                        <span>Preview Section</span>
                    </a>

                    <a
                        href="{{ route('admin.course-management.final-exam-reviews.index', $section) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-50 px-4 py-2.5 text-xs font-bold text-amber-700 shadow-sm transition hover:bg-amber-100">
                        <x-admin.icon name="eye" class="h-4 w-4" />
                        <span>Review Attempts</span>
                    </a>

                    <form action="{{ route('admin.course-management.final-exams.toggle-active', $section) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        @if ($section->is_active)
                        <button
                            type="submit"
                            onclick="return confirm('Deactivating this section removes it from the active completion requirements for students who have not completed the course. Existing attempts and issued certificates will remain unchanged. Continue?');"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-100">
                            <span>Deactivate Section</span>
                        </button>
                        @else
                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100">
                            <span>Activate Section</span>
                        </button>
                        @endif
                    </form>

                    @if ($section->attempts_count === 0)
                    <form action="{{ route('admin.course-management.final-exams.destroy', $section) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            onclick="return confirm('Are you sure you want to delete this Final Exam section? This action cannot be undone.');"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-xs font-bold text-rose-700 transition hover:bg-rose-100">
                            <x-admin.icon name="x" class="h-3.5 w-3.5" />
                            <span>Delete Section</span>
                        </button>
                    </form>
                    @else
                    <button
                        type="button"
                        disabled
                        title="This Final Exam section already has student attempts and cannot be deleted. Deactivate it instead."
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-xs font-bold text-slate-400 cursor-not-allowed">
                        <span>Cannot Delete</span>
                    </button>
                    @endif
                </div>
            </div>
        </x-admin.table-card>
        @endforeach
    </div>
    @endif
</section>
@endsection