@extends('layouts.admin', [
'pageTitle' => 'Dashboard',
'pageSubtitle' => 'System Overview',
])

@section('content')
@php
$metrics = [
[
'title' => 'Total Students',
'value' => '1,284',
'description' => '+12.5%',
'accent' => 'blue',
'icon' => 'users',
],
[
'title' => 'Total Courses',
'value' => '42',
'description' => 'Stable',
'accent' => 'blue',
'icon' => 'book',
],
[
'title' => 'Pending Orders',
'value' => '12',
'description' => 'Action required',
'accent' => 'gold',
'icon' => 'cart',
],
[
'title' => 'Issued Certificates',
'value' => '740',
'description' => '+8.1%',
'accent' => 'blue',
'icon' => 'certificate',
],
];

$activities = [
[
'initials' => 'SJ',
'name' => 'Sarah Johnson',
'description' => 'Purchased Business English Advanced',
'variant' => 'pending',
'avatar' => 'rose',
],
[
'initials' => 'AW',
'name' => 'Ahmad Wijaya',
'description' => 'Purchased IELTS Foundation',
'variant' => 'pending',
'avatar' => 'blue',
],
[
'initials' => 'CS',
'name' => 'Clara Smith',
'description' => 'Purchased Academic Writing',
'variant' => 'completed',
'avatar' => 'green',
],
];

$transactions = [
[
'orderId' => '#ORD-2024-8291',
'student' => 'Sarah Johnson',
'course' => 'Business English Adv.',
'price' => 'Rp 2,500,000',
'status' => 'Pending',
'statusVariant' => 'pending',
'date' => 'June 12, 2024',
],
[
'orderId' => '#ORD-2024-8290',
'student' => 'Ahmad Wijaya',
'course' => 'IELTS Foundation',
'price' => 'Rp 3,200,000',
'status' => 'Pending',
'statusVariant' => 'pending',
'date' => 'June 12, 2024',
],
[
'orderId' => '#ORD-2024-8289',
'student' => 'Clara Smith',
'course' => 'Academic Writing',
'price' => 'Rp 1,800,000',
'status' => 'Completed',
'statusVariant' => 'completed',
'date' => 'June 11, 2024',
],
[
'orderId' => '#ORD-2024-8288',
'student' => 'Mark Peterson',
'course' => 'Intensive TOEFL Prep',
'price' => 'Rp 4,500,000',
'status' => 'Completed',
'statusVariant' => 'completed',
'date' => 'June 11, 2024',
],
];
@endphp

<section class="space-y-8">
    @include('partials.admin.dashboard.header')
    @include('partials.admin.dashboard.metrics')

    <div class="grid gap-6 xl:grid-cols-[2fr_1fr]">
        @include('partials.admin.dashboard.revenue-analytics')
        @include('partials.admin.dashboard.recent-activity')
    </div>

    @include('partials.admin.dashboard.recent-transactions')
</section>
@endsection