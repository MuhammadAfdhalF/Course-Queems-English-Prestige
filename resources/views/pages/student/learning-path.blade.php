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
    @include('partials.student.learning-path.back-link')
    @include('partials.student.learning-path.hero')
    @include('partials.student.learning-path.overview')
    @include('partials.student.learning-path.modules')
    @include('partials.student.learning-path.final-exam')
</section>
@endsection