@extends('layouts.admin', [
'pageTitle' => 'Course Orders',
'pageSubtitle' => 'Enrollment Management',
])

@section('content')
@php
$tabs = [
['key' => 'pending', 'label' => 'Pending', 'count' => 12],
['key' => 'approved', 'label' => 'Approved', 'count' => 856],
['key' => 'rejected', 'label' => 'Rejected', 'count' => 42],
];

$orders = [
[
'id' => '#QE-9921',
'studentName' => 'Jane Cooper',
'studentEmail' => 'j.cooper@example.com',
'studentInitials' => 'JC',
'avatarColor' => 'bg-blue-100 text-blue-700',
'course' => 'Advanced IELTS Prep',
'price' => '$450.00',
'status' => 'pending',
'statusLabel' => 'Pending',
'orderDate' => 'Oct 24, 2023',
'whatsapp' => '+62 812-3456-7890',
],
[
'id' => '#QE-9918',
'studentName' => 'Robert Bennett',
'studentEmail' => 'robert.b@gmail.com',
'studentInitials' => 'RB',
'avatarColor' => 'bg-purple-100 text-purple-700',
'course' => 'Business English Elite',
'price' => '$1,200.00',
'status' => 'approved',
'statusLabel' => 'Approved',
'orderDate' => 'Oct 23, 2023',
'whatsapp' => '+62 813-2222-9811',
],
[
'id' => '#QE-9915',
'studentName' => 'Arlene Steward',
'studentEmail' => 'arlene77@me.com',
'studentInitials' => 'AS',
'avatarColor' => 'bg-slate-100 text-slate-700',
'course' => 'General English 101',
'price' => '$150.00',
'status' => 'rejected',
'statusLabel' => 'Rejected',
'orderDate' => 'Oct 23, 2023',
'whatsapp' => '+62 821-7777-1120',
],
[
'id' => '#QE-9912',
'studentName' => 'Cody Howard',
'studentEmail' => 'cody.howard@outlook.com',
'studentInitials' => 'CH',
'avatarColor' => 'bg-yellow-100 text-yellow-700',
'course' => 'Advanced IELTS Prep',
'price' => '$450.00',
'status' => 'pending',
'statusLabel' => 'Pending',
'orderDate' => 'Oct 22, 2023',
'whatsapp' => '+62 812-9988-4412',
],
];
@endphp

<section
    x-data="{
        activeTab: 'pending',
        search: '',
        orderModalOpen: false,
        selectedOrder: null,
        orders: @js($orders),

        openOrder(order) {
            this.selectedOrder = order;
            this.orderModalOpen = true;
        },

        matches(order) {
            const tabMatch = this.activeTab === 'all' || order.status === this.activeTab;

            const keyword = this.search.toLowerCase();
            const searchMatch =
                order.id.toLowerCase().includes(keyword) ||
                order.studentName.toLowerCase().includes(keyword) ||
                order.studentEmail.toLowerCase().includes(keyword) ||
                order.course.toLowerCase().includes(keyword);

            return tabMatch && searchMatch;
        },

        approveOrder() {
            alert('Order approved successfully.');
            this.orderModalOpen = false;
        },

        rejectOrder() {
            alert('Order rejected.');
            this.orderModalOpen = false;
        }
    }"
    class="mx-auto max-w-7xl space-y-6">
    @include('partials.admin.orders.header')
    @include('partials.admin.orders.tabs')
    @include('partials.admin.orders.filters')
    @include('partials.admin.orders.table')
    @include('partials.admin.orders.order-detail-modal')
</section>
@endsection