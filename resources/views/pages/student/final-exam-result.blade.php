@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-5xl space-y-10 py-6">
    @include('partials.student.final-exam-result.hero')
    @include('partials.student.final-exam-result.score')
    @include('partials.student.final-exam-result.content')
</section>
@endsection