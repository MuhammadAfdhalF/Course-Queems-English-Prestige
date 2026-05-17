@extends('layouts.admin', [
'pageTitle' => 'Dashboard',
'pageSubtitle' => 'System Overview',
])

@section('content')
<section class="space-y-8">
    @include('partials.admin.dashboard.header')

    @include('partials.admin.dashboard.metrics')

    <div class="grid gap-6 xl:grid-cols-[2fr_1fr]">
        @include('partials.admin.dashboard.revenue-analytics')
        @include('partials.admin.dashboard.action-center')
    </div>

    <div class="grid gap-6 xl:grid-cols-[1fr_1fr]">
        @include('partials.admin.dashboard.recent-activity')
        @include('partials.admin.dashboard.waiting-reviews')
    </div>

    @include('partials.admin.dashboard.recent-transactions')
</section>
@endsection