@extends('layouts.admin', [
'pageTitle' => 'Final Exam Attempts',
'pageSubtitle' => $finalExam->title,
])

@section('content')
@php
    $builderUrl = route('admin.course-management.programs.builder', [
        'courseProgram' => $finalExam->courseLevel->course_program_id,
        'level' => $finalExam->course_level_id,
        'exam' => $finalExam->id
    ]);
@endphp

<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="$builderUrl"
        back-label="Back to Course Builder" />

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
                    {{ $attempts->count() }} attempts
                </span>

                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                    Passing Grade: {{ $finalExam->passing_grade }}%
                </span>
            </div>
        </div>
    </x-admin.table-card>

    <x-admin.data-table>
        <x-slot:head>
            <th class="px-6 py-4">Student</th>
            <th class="px-6 py-4">Attempt</th>
            <th class="px-6 py-4">Score</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Submitted</th>
            <th class="w-[160px] px-6 py-4 text-center">Action</th>
        </x-slot:head>

        @forelse ($attempts as $attempt)
        @php
        $statusClasses = match ($attempt->status) {
        'passed' => 'bg-emerald-50 text-emerald-700',
        'failed' => 'bg-rose-50 text-rose-700',
        'waiting_review' => 'bg-amber-50 text-amber-700',
        'in_progress' => 'bg-slate-100 text-slate-500',
        default => 'bg-slate-100 text-slate-600',
        };

        $statusLabel = match ($attempt->status) {
        'passed' => 'Passed',
        'failed' => 'Failed',
        'waiting_review' => 'Waiting Review',
        'in_progress' => 'In Progress',
        default => ucfirst(str_replace('_', ' ', $attempt->status)),
        };
        @endphp

        <tr class="text-sm text-slate-700 transition hover:bg-slate-50/80">
            <td class="px-6 py-5">
                <p class="font-extrabold text-slate-900">
                    {{ $attempt->student?->name ?? 'Unknown Student' }}
                </p>

                <p class="mt-1 text-xs font-semibold text-slate-400">
                    {{ $attempt->student?->email ?? '-' }}
                </p>
            </td>

            <td class="px-6 py-5">
                <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-xl bg-slate-100 px-3 text-sm font-extrabold text-slate-700">
                    #{{ $attempt->attempt_number }}
                </span>
            </td>

            <td class="px-6 py-5">
                <p class="font-extrabold text-slate-900">
                    {{ number_format((float) $attempt->total_score, 2) }}%
                </p>
            </td>

            <td class="px-6 py-5">
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses }}">
                    {{ $statusLabel }}
                </span>
            </td>

            <td class="px-6 py-5">
                <p class="text-sm font-semibold text-slate-700">
                    {{ $attempt->submitted_at?->format('d M Y') ?? '-' }}
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    {{ $attempt->submitted_at?->format('H:i') ?? '' }}
                </p>
            </td>

            <td class="px-6 py-5">
                <div class="flex justify-center">
                    <a
                        href="{{ route('admin.course-management.final-exam-reviews.show', $attempt) }}"
                        class="inline-flex h-9 items-center justify-center gap-2 rounded-lg bg-[var(--color-brand-blue)] px-4 text-xs font-bold text-white transition hover:opacity-90">
                        <x-admin.icon name="eye" class="h-4 w-4" />
                        <span>Review</span>
                    </a>
                </div>
            </td>
        </tr>
        @empty
        <x-admin.empty-state
            colspan="6"
            title="No attempts yet"
            description="Student final exam attempts will appear here after they submit the final exam." />
        @endforelse
    </x-admin.data-table>
</section>
@endsection