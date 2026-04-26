@extends('layouts.student')

@section('content')
@php
$modules = [
[
'number' => '01',
'title' => 'Introduction to TOEFL Structure',
'status' => 'completed',
'buttonText' => 'Review',
],
[
'number' => '02',
'title' => 'Listening Strategies & Practice',
'status' => 'completed',
'buttonText' => 'Review',
],
[
'number' => '03',
'title' => 'Advanced Reading Comprehension',
'status' => 'current',
'buttonText' => 'Continue',
],
[
'number' => '04',
'title' => 'Speaking with Confidence',
'status' => 'locked',
'buttonText' => 'Start',
'note' => 'Complete previous module to unlock',
],
[
'number' => '05',
'title' => 'Academic Writing Excellence',
'status' => 'locked',
'buttonText' => 'Start',
],
];
@endphp

<section class="mx-auto max-w-7xl space-y-8">
    <div class="reveal">
        <a href="{{ route('student.my-courses') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>
            Back to My Courses
        </a>
    </div>

    <div class="reveal reveal-delay-1">
        <x-student.course-learning-hero />
    </div>

    <div class="reveal reveal-delay-1">
        <x-student.course-overview-panel />
    </div>

    <div class="space-y-4">
        <h2 class="reveal text-2xl font-bold text-slate-900">Course Modules</h2>

        <div class="space-y-4">
            @foreach ($modules as $index => $module)
            @php
            $delayClass = match ($index) {
            1 => 'reveal-delay-1',
            2 => 'reveal-delay-2',
            3 => 'reveal-delay-3',
            4 => 'reveal-delay-4',
            default => '',
            };
            @endphp

            <div class="reveal {{ $delayClass }}">
                <x-student.course-module-item
                    :number="$module['number']"
                    :title="$module['title']"
                    :status="$module['status']"
                    :button-text="$module['buttonText']"
                    :note="$module['note'] ?? ''" />
            </div>
            @endforeach
        </div>
    </div>

    <div class="reveal reveal-delay-2">
        <x-student.final-exam-panel />
    </div>
</section>
@endsection