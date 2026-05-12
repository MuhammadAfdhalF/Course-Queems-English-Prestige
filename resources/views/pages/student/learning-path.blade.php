@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-7xl space-y-8">
    @include('partials.student.learning-path.back-link')
    @include('partials.student.learning-path.hero')
    @include('partials.student.learning-path.overview')
    @include('partials.student.learning-path.modules')
    @include('partials.student.learning-path.final-exam')
</section>
@endsection