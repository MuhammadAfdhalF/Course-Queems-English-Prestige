@extends('layouts.admin', [
    'pageTitle' => 'Dashboard',
    'pageSubtitle' => 'System Overview',
])

@section('content')
<section class="space-y-5 sm:space-y-6">
    @include('partials.admin.dashboard.header')

    @include('partials.admin.dashboard.metrics')

    <div class="grid gap-5 xl:grid-cols-12 xl:items-start">
        <div class="xl:col-span-7">
            @include('partials.admin.dashboard.revenue-analytics')
        </div>
        <div class="xl:col-span-5">
            @include('partials.admin.dashboard.action-center')
        </div>
    </div>

    <div class="grid gap-5 xl:grid-cols-12 xl:items-start">
        <div class="xl:col-span-5">
            @include('partials.admin.dashboard.waiting-reviews')
        </div>
        <div class="xl:col-span-7">
            @include('partials.admin.dashboard.recent-activity')
        </div>
    </div>

    @include('partials.admin.dashboard.recent-transactions')
</section>
@endsection