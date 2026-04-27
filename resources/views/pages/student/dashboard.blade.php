@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-7xl space-y-8">
    @include('partials.student.dashboard.pending-banner-section')
    @include('partials.student.dashboard.header')
    @include('partials.student.dashboard.hero-cards')
    @include('partials.student.dashboard.continue-learning')
    @include('partials.student.dashboard.summary-stats')
    @include('partials.student.dashboard.actions')
</section>
@endsection