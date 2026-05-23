@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    @include('partials.student.dashboard.hero')
    @include('partials.student.dashboard.learning-snapshot')
    @include('partials.student.dashboard.priority-alerts')
    @include('partials.student.dashboard.learning-list')
    @include('partials.student.dashboard.achievement-actions')
</section>
@endsection