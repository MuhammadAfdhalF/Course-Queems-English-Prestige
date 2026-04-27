@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-6xl space-y-8">
    @include('partials.student.module-material.back-link')
    @include('partials.student.module-material.hero')
    @include('partials.student.module-material.content')
    @include('partials.student.module-material.footer-cta')
</section>
@endsection