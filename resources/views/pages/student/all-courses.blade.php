@extends('layouts.student')

@section('content')
<section class="-mx-4 -mt-6 overflow-hidden bg-white lg:-mx-8">
    @include('partials.student.all-courses.hero')
    @include('partials.student.all-courses.filters')
    @include('partials.student.all-courses.grid')
</section>
@endsection