@extends('layouts.admin', [
'pageTitle' => 'Grant Course Access',
'pageSubtitle' => 'Course Access',
])

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-access.index')"
        back-label="Back to Course Access" />

    <x-admin.flash-message />

    <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
        <x-admin.table-card class="overflow-hidden">
            <div class="border-b border-slate-200 bg-gradient-to-br from-slate-950 via-[var(--color-brand-blue)] to-slate-800 px-6 py-6 text-white">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-white/50">
                    Manual Access
                </p>

                <h2 class="mt-2 text-2xl font-black">
                    Grant Course Access
                </h2>

                <p class="mt-2 max-w-3xl text-sm font-semibold leading-6 text-white/70">
                    Give a student direct access to an active course without creating a public order.
                </p>
            </div>

            <form action="{{ route('admin.course-access.store') }}" method="POST" class="space-y-6 p-6">
                @csrf

                <div>
                    <label for="student_id" class="mb-2 block text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        Student
                    </label>

                    <select
                        id="student_id"
                        name="student_id"
                        required
                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
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
                    <label for="course_level_id" class="mb-2 block text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        Course
                    </label>

                    <select
                        id="course_level_id"
                        name="course_level_id"
                        required
                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
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
                    <label for="note" class="mb-2 block text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        Note
                    </label>

                    <textarea
                        id="note"
                        name="note"
                        rows="5"
                        placeholder="Optional note for this manual access..."
                        class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('note') }}</textarea>

                    @error('note')
                    <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                    <a
                        href="{{ route('admin.course-access.index') }}"
                        class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-black text-slate-700 transition hover:bg-slate-50">
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-black text-white shadow-sm transition hover:opacity-90">
                        Grant Access
                    </button>
                </div>
            </form>
        </x-admin.table-card>

        <div class="space-y-6">
            <x-admin.table-card class="p-6">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                    Access Rules
                </p>

                <h3 class="mt-2 text-xl font-black text-slate-900">
                    Before granting access
                </h3>

                <div class="mt-5 space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-black text-slate-900">Student must be active</p>
                        <p class="mt-1 text-xs font-semibold leading-5 text-slate-500">Inactive students cannot receive manual course access.</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-black text-slate-900">Course must be active</p>
                        <p class="mt-1 text-xs font-semibold leading-5 text-slate-500">Only active course levels can be selected here.</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-black text-slate-900">Duplicate access is blocked</p>
                        <p class="mt-1 text-xs font-semibold leading-5 text-slate-500">Students cannot receive duplicate active or completed access to the same course.</p>
                    </div>

                    <div class="rounded-2xl border border-amber-100 bg-amber-50 p-4">
                        <p class="text-sm font-black text-amber-800">Pending order warning</p>
                        <p class="mt-1 text-xs font-semibold leading-5 text-amber-700">If the student has a pending order, approve or reject that order instead.</p>
                    </div>
                </div>
            </x-admin.table-card>

            <x-admin.table-card class="border-blue-100 bg-blue-50 p-6">
                <p class="text-sm font-black text-blue-800">
                    What happens after granting?
                </p>

                <p class="mt-2 text-sm font-semibold leading-6 text-blue-700">
                    The enrollment will be created as active, source will be manual, progress starts from 0%, and the student can access the course from their My Courses page.
                </p>
            </x-admin.table-card>
        </div>
    </div>
</section>
@endsection