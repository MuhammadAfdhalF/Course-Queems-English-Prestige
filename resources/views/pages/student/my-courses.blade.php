@extends('layouts.student')

@section('content')
<section
    x-data="{
        activeTab: 'all',
        search: '',
        matches(status, title) {
            const tabMatch = this.activeTab === 'all' || this.activeTab === status;
            const searchMatch = title.toLowerCase().includes(this.search.toLowerCase());

            return tabMatch && searchMatch;
        }
    }"
    class="mx-auto max-w-7xl space-y-8">
    <div class="grid gap-8 xl:grid-cols-[minmax(0,1.9fr)_360px]">
        <div class="min-w-0">
            @include('partials.student.my-courses.header')
            @include('partials.student.my-courses.tabs')
            @include('partials.student.my-courses.course-list')
        </div>

        @include('partials.student.my-courses.sidebar')
    </div>
</section>
@endsection