@extends('layouts.admin', [
'pageTitle' => 'Course Access Detail',
'pageSubtitle' => $student->name,
])

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-access.index')"
        back-label="Back to Course Access" />

    <x-admin.flash-message />

    @include('partials.admin.course-access.detail-profile')

    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        @include('partials.admin.course-access.detail-modules')
        @include('partials.admin.course-access.detail-certificate')
    </div>

    @include('partials.admin.course-access.detail-assessments')

    @if ($canCancel)
    <x-admin.table-card class="border-rose-200 bg-rose-50 p-6">
        <p class="text-xs font-black uppercase tracking-[0.18em] text-rose-500">
            Danger Zone
        </p>

        <h2 class="mt-2 text-xl font-black text-rose-700">
            Cancel Course Access
        </h2>

        <p class="mt-2 max-w-3xl text-sm font-semibold leading-6 text-rose-600">
            Cancelling access will change this enrollment status to cancelled. Student learning data will not be deleted.
        </p>

        <form
            action="{{ route('admin.course-access.cancel', $enrollment) }}"
            method="POST"
            class="mt-5 space-y-4"
            onsubmit="return confirm('Are you sure you want to cancel this course access?')">
            @csrf
            @method('PUT')

            <textarea
                name="cancel_note"
                rows="4"
                placeholder="Write cancellation reason..."
                class="w-full resize-none rounded-xl border border-rose-200 bg-white px-4 py-4 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-100"></textarea>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="inline-flex h-12 items-center justify-center rounded-xl bg-rose-600 px-6 text-sm font-black text-white shadow-sm transition hover:bg-rose-700">
                    Cancel Access
                </button>
            </div>
        </form>
    </x-admin.table-card>
    @endif
</section>
@endsection