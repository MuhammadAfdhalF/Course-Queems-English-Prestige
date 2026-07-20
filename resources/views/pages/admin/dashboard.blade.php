@extends('layouts.admin', [
'pageTitle' => 'Dashboard',
'pageSubtitle' => 'System Overview',
])

@section('content')
<section class="space-y-7">
    @include('partials.admin.dashboard.header')

    @include('partials.admin.dashboard.metrics')

    <div class="grid gap-6 xl:grid-cols-[1.65fr_0.95fr]">
        @include('partials.admin.dashboard.revenue-analytics')
        @include('partials.admin.dashboard.action-center')
    </div>

    <div class="grid gap-6 xl:grid-cols-[0.95fr_1.05fr]">
        @include('partials.admin.dashboard.waiting-reviews')
        @include('partials.admin.dashboard.recent-activity')
    </div>

    @include('partials.admin.dashboard.recent-transactions')
</section>
@endsection