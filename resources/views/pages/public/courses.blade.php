@extends('layouts.public')

@section('content')
    @include('partials.public.courses.hero')
    @include('partials.public.courses.filters')
    @include('partials.public.courses.grid')
    @include('partials.public.courses.cta')
@endsection