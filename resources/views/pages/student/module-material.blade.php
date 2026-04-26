@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-6xl space-y-8">
    <div class="reveal">
        <a href="{{ route('student.learning-path') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Module List
        </a>
    </div>

    <div class="reveal reveal-delay-1">
        <x-student.module-material-hero />
    </div>

    <div class="reveal reveal-delay-1">
        <x-student.module-material-content />
    </div>

    <div class="reveal reveal-delay-2">
        <x-student.module-material-footer-cta />
    </div>
</section>
@endsection