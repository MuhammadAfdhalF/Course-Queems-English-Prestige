@extends('layouts.admin', [
'pageTitle' => 'Student Detail',
'pageSubtitle' => $student->name,
])

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.students.index')"
        back-label="Back to Students" />

    <x-admin.flash-message />

    @include('partials.admin.students.detail-profile')

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
        @foreach ($stats as $stat)
        @php
        $toneClasses = match ($stat['tone']) {
        'emerald' => 'bg-emerald-50 text-emerald-700',
        'amber' => 'bg-amber-50 text-amber-700',
        default => 'bg-blue-50 text-blue-700',
        };
        @endphp

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-[11px] font-black uppercase tracking-[0.16em] text-slate-400">
                {{ $stat['label'] }}
            </p>

            <p class="mt-3 text-3xl font-black {{ $toneClasses }} rounded-xl px-3 py-2">
                {{ $stat['value'] }}
            </p>

            <p class="mt-3 text-xs font-semibold leading-5 text-slate-500">
                {{ $stat['description'] }}
            </p>
        </div>
        @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        @include('partials.admin.students.detail-enrollments')
        @include('partials.admin.students.detail-orders')
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        @include('partials.admin.students.detail-certificates')
        @include('partials.admin.students.detail-testimonials')
    </div>

    @include('partials.admin.students.detail-assessments')
</section>
@endsection