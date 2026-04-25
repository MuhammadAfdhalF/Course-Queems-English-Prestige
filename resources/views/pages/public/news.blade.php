@extends('layouts.public')

@section('content')
    @include('partials.public.news.hero')
    @include('partials.public.news.filters')
    @include('partials.public.news.grid')
    @include('partials.public.news.pagination')
@endsection