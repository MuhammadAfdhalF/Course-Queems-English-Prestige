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
        'emerald' => [
        'card' => 'border-emerald-100 bg-gradient-to-br from-white to-emerald-50',
        'value' => 'text-emerald-700',
        'icon' => 'bg-emerald-100 text-emerald-700',
        ],
        'amber' => [
        'card' => 'border-amber-100 bg-gradient-to-br from-white to-amber-50',
        'value' => 'text-amber-700',
        'icon' => 'bg-amber-100 text-amber-700',
        ],
        default => [
        'card' => 'border-blue-100 bg-gradient-to-br from-white to-blue-50',
        'value' => 'text-[var(--color-brand-blue)]',
        'icon' => 'bg-blue-100 text-[var(--color-brand-blue)]',
        ],
        };
        @endphp

        <div class="rounded-2xl border {{ $toneClasses['card'] }} p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.16em] text-slate-400">
                        {{ $stat['label'] }}
                    </p>

                    <p class="mt-3 text-3xl font-black {{ $toneClasses['value'] }}">
                        {{ $stat['value'] }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $toneClasses['icon'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V5M4 19h16M8 16v-5M12 16V8M16 16v-3" />
                    </svg>
                </div>
            </div>

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