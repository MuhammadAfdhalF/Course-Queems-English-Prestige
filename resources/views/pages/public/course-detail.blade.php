@extends('layouts.public')

@section('content')
<section class="bg-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 lg:grid-cols-[1.85fr_0.75fr] lg:px-8 lg:py-14">
        <div class="min-w-0">
            @include('partials.public.course-detail.hero')
            @include('partials.public.course-detail.content')
        </div>

        <div class="min-w-0">
            @include('partials.public.course-detail.sidebar-card')
        </div>
    </div>
</section>
@endsection