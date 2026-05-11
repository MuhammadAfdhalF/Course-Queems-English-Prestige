@extends('layouts.admin', [
'pageTitle' => 'Course Orders',
'pageSubtitle' => 'Enrollment Management',
])

@section('content')
<section
    x-data="{
        activeTab: 'pending',
        search: '',
        orderModalOpen: false,
        selectedOrder: null,
        adminNote: '',
        orders: @js($orders),

        openOrder(order) {
            this.selectedOrder = order;
            this.adminNote = order.note || '';
            this.orderModalOpen = true;
        },

        closeOrder() {
            this.orderModalOpen = false;
            this.selectedOrder = null;
            this.adminNote = '';
        },

        matches(order) {
            const tabMatch = this.activeTab === 'all' || order.status === this.activeTab;

            const keyword = this.search.toLowerCase();
            const searchMatch =
                order.id.toLowerCase().includes(keyword) ||
                order.studentName.toLowerCase().includes(keyword) ||
                order.studentEmail.toLowerCase().includes(keyword) ||
                order.course.toLowerCase().includes(keyword) ||
                order.program.toLowerCase().includes(keyword);

            return tabMatch && searchMatch;
        },
    }"
    class="mx-auto max-w-7xl space-y-6">

    @if (session('success'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-bold text-rose-700">
        {{ session('error') }}
    </div>
    @endif

    @include('partials.admin.orders.header')
    @include('partials.admin.orders.tabs')
    @include('partials.admin.orders.filters')
    @include('partials.admin.orders.table')
    @include('partials.admin.orders.order-detail-modal')
</section>
@endsection