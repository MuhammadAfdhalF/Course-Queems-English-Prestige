@extends('layouts.admin', [
'pageTitle' => 'Grant Course Access',
'pageSubtitle' => 'Course Access',
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-access.index')"
        back-label="Back to Course Access" />

    <x-admin.flash-message />

    <x-admin.table-card class="overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
            <h2 class="text-2xl font-black text-slate-900">
                Manual Grant Access
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Give a student direct access to an active course without creating an order.
            </p>
        </div>

        <form action="{{ route('admin.course-access.store') }}" method="POST" class="space-y-6 p-6">
            @csrf

            <div>
                <label for="student_id" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    Student
                </label>

                <select
                    id="student_id"
                    name="student_id"
                    required
                    class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Select Student</option>

                    @foreach ($students as $student)
                    <option value="{{ $student->id }}" @selected((int) old('student_id')===$student->id)>
                        {{ $student->name }} — {{ $student->email }}
                    </option>
                    @endforeach
                </select>

                @error('student_id')
                <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="course_level_id" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    Course
                </label>

                <select
                    id="course_level_id"
                    name="course_level_id"
                    required
                    class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Select Course</option>

                    @foreach ($courseLevels as $courseLevel)
                    <option value="{{ $courseLevel->id }}" @selected((int) old('course_level_id')===$courseLevel->id)>
                        {{ $courseLevel->name }} — {{ $courseLevel->courseProgram?->name ?? 'Program' }}
                    </option>
                    @endforeach
                </select>

                @error('course_level_id')
                <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="note" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    Note
                </label>

                <textarea
                    id="note"
                    name="note"
                    rows="5"
                    placeholder="Optional note for this manual access..."
                    class="w-full resize-none rounded-xl border border-slate-200 px-4 py-4 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('note') }}</textarea>

                @error('note')
                <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                <a
                    href="{{ route('admin.course-access.index') }}"
                    class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                    Grant Access
                </button>
            </div>
        </form>
    </x-admin.table-card>
</section>
@endsection