@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-5xl space-y-10 py-6">
    <div class="reveal">
        <x-student.final-exam-result-hero />
    </div>

    <div class="reveal reveal-delay-1">
        <x-student.final-exam-score-card />
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.1fr_1fr]">
        <div class="reveal reveal-delay-2">
            <x-student.final-exam-summary-panel />
        </div>

        <div class="reveal reveal-delay-3">
            <x-student.final-exam-certificate-card />
        </div>
    </div>
</section>
@endsection