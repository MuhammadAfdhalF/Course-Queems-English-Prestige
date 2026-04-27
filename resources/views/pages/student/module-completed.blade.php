@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-4xl space-y-8 py-6">
    @include('partials.student.module-completed.hero')
    @include('partials.student.module-completed.performance')
    @include('partials.student.module-completed.unlock-next')
    @include('partials.student.module-completed.actions')
</section>
@endsection