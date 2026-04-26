@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-4xl space-y-8 py-6">
    <div class="reveal">
        <x-student.module-complete-hero />
    </div>

    <div class="reveal reveal-delay-1">
        <x-student.performance-score-card />
    </div>

    <div class="reveal reveal-delay-2">
        <x-student.next-module-unlock-card />
    </div>

    <div class="reveal reveal-delay-3">
        <x-student.module-complete-actions />
    </div>
</section>
@endsection