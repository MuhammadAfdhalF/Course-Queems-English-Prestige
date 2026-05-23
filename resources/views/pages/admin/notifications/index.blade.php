@extends('layouts.admin', [
'pageTitle' => 'Notifications',
'pageSubtitle' => 'Admin Center',
])

@section('content')
@php
$typeLabel = function (?string $type) {
return match ($type) {
'order' => 'Order',
'payment' => 'Payment',
'practice_review' => 'Practice',
'final_exam_review' => 'Final Exam',
'testimonial' => 'Testimonial',
default => ucfirst(str_replace('_', ' ', (string) $type)),
};
};

$typeTone = function (?string $type) {
return match ($type) {
'order' => [
'badge' => 'bg-blue-100 text-blue-700',
'icon' => 'bg-blue-50 text-blue-700',
'ring' => 'border-blue-100',
],
'payment' => [
'badge' => 'bg-emerald-100 text-emerald-700',
'icon' => 'bg-emerald-50 text-emerald-700',
'ring' => 'border-emerald-100',
],
'practice_review' => [
'badge' => 'bg-indigo-100 text-indigo-700',
'icon' => 'bg-indigo-50 text-indigo-700',
'ring' => 'border-indigo-100',
],
'final_exam_review' => [
'badge' => 'bg-amber-100 text-amber-700',
'icon' => 'bg-amber-50 text-amber-700',
'ring' => 'border-amber-100',
],
'testimonial' => [
'badge' => 'bg-purple-100 text-purple-700',
'icon' => 'bg-purple-50 text-purple-700',
'ring' => 'border-purple-100',
],
default => [
'badge' => 'bg-slate-100 text-slate-700',
'icon' => 'bg-slate-50 text-slate-700',
'ring' => 'border-slate-100',
],
};
};

$activeStatusClass = 'bg-[var(--color-brand-blue)] text-white border-[var(--color-brand-blue)]';
$inactiveStatusClass = 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50';
@endphp

<section class="mx-auto max-w-6xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.dashboard')"
        back-label="Back to Dashboard">
        <x-slot:actions>
            @if ($unreadCount > 0)
            <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                @csrf
                @method('PUT')

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-black text-white shadow-sm transition hover:opacity-90">
                    Mark All as Read
                </button>
            </form>
            @endif
        </x-slot:actions>
    </x-admin.page-toolbar>

    <x-admin.flash-message />

    <x-admin.table-card class="overflow-hidden">
        <div class="grid gap-0 lg:grid-cols-[1fr_auto] lg:items-stretch">
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-[var(--color-brand-blue)] to-slate-800 p-7 text-white">
                <div class="pointer-events-none absolute -right-16 -top-16 h-44 w-44 rounded-full bg-white/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-20 left-20 h-44 w-44 rounded-full bg-yellow-400/20 blur-3xl"></div>

                <div class="relative">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-white/50">
                        Notification Inbox
                    </p>

                    <h2 class="mt-3 text-3xl font-black leading-tight">
                        Admin Notifications
                    </h2>

                    <p class="mt-3 max-w-3xl text-sm font-semibold leading-7 text-white/70">
                        Monitor orders, payments, student submissions, review requests, and testimonials from one inbox.
                    </p>
                </div>
            </div>

            <div class="grid gap-3 bg-white p-6 sm:grid-cols-3">
                <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.16em] text-blue-500">
                        Total
                    </p>
                    <p class="mt-2 text-3xl font-black text-blue-700">
                        {{ $totalCount }}
                    </p>
                    <p class="mt-1 text-xs font-semibold text-blue-500/70">
                        All notifications
                    </p>
                </div>

                <div class="rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.16em] text-amber-500">
                        Unread
                    </p>
                    <p class="mt-2 text-3xl font-black text-amber-700">
                        {{ $unreadCount }}
                    </p>
                    <p class="mt-1 text-xs font-semibold text-amber-500/70">
                        Needs attention
                    </p>
                </div>

                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.16em] text-emerald-500">
                        Read
                    </p>
                    <p class="mt-2 text-3xl font-black text-emerald-700">
                        {{ $readCount }}
                    </p>
                    <p class="mt-1 text-xs font-semibold text-emerald-500/70">
                        Already checked
                    </p>
                </div>
            </div>
        </div>
    </x-admin.table-card>

    <x-admin.table-card class="p-5">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                    Filter Inbox
                </p>

                <div class="mt-3 flex flex-wrap gap-2">
                    <a
                        href="{{ route('admin.notifications.index', ['status' => 'all', 'type' => $type]) }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border px-4 text-sm font-black transition {{ $status === 'all' ? $activeStatusClass : $inactiveStatusClass }}">
                        All
                    </a>

                    <a
                        href="{{ route('admin.notifications.index', ['status' => 'unread', 'type' => $type]) }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border px-4 text-sm font-black transition {{ $status === 'unread' ? $activeStatusClass : $inactiveStatusClass }}">
                        Unread
                    </a>

                    <a
                        href="{{ route('admin.notifications.index', ['status' => 'read', 'type' => $type]) }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border px-4 text-sm font-black transition {{ $status === 'read' ? $activeStatusClass : $inactiveStatusClass }}">
                        Read
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.notifications.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <input type="hidden" name="status" value="{{ $status }}">

                <div>
                    <label for="type" class="mb-2 block text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        Type
                    </label>

                    <select
                        id="type"
                        name="type"
                        class="h-11 min-w-[230px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-bold text-slate-700 focus:border-[var(--color-brand-blue)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                        @foreach ($typeOptions as $typeValue => $typeName)
                        <option value="{{ $typeValue }}" @selected($type===$typeValue)>
                            {{ $typeName }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-black text-white shadow-sm transition hover:opacity-90">
                        Apply
                    </button>

                    @if ($status !== 'all' || $type !== 'all')
                    <a
                        href="{{ route('admin.notifications.index') }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-black text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </x-admin.table-card>

    <div class="space-y-4">
        @forelse ($notifications as $notification)
        @php
        $tone = $typeTone($notification->type);
        @endphp

        <x-admin.table-card
            class="overflow-hidden {{ $notification->is_read ? 'bg-white' : 'border-blue-200 bg-blue-50/30' }}">
            <div class="p-5">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex min-w-0 flex-1 gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border {{ $tone['icon'] }} {{ $tone['ring'] }}">
                            @if ($notification->type === 'payment')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8h18M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2zM7 15h4" />
                            </svg>
                            @elseif ($notification->type === 'practice_review')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-3-2-3 2-3-2-3 2V6a2 2 0 012-2z" />
                            </svg>
                            @elseif ($notification->type === 'final_exam_review')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4h10a2 2 0 012 2v14l-3-2-3 2-3-2-3 2V6a2 2 0 012-2zM9 9h6M9 13h6" />
                            </svg>
                            @elseif ($notification->type === 'testimonial')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 5a3 3 0 016 0" />
                            </svg>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-black {{ $tone['badge'] }}">
                                    {{ $typeLabel($notification->type) }}
                                </span>

                                @if (! $notification->is_read)
                                <span class="inline-flex rounded-full bg-[var(--color-brand-gold)] px-3 py-1 text-xs font-black text-white">
                                    Unread
                                </span>
                                @else
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-500">
                                    Read
                                </span>
                                @endif

                                <span class="text-xs font-black uppercase tracking-[0.14em] text-slate-400">
                                    {{ $notification->created_at?->diffForHumans() }}
                                </span>
                            </div>

                            <h3 class="mt-3 text-lg font-black text-slate-900">
                                {{ $notification->title }}
                            </h3>

                            <p class="mt-2 text-sm font-semibold leading-6 text-slate-600">
                                {{ $notification->message }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col lg:items-stretch">
                        <a
                            href="{{ route('admin.notifications.open', $notification) }}"
                            class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-black text-white transition hover:opacity-90">
                            Open
                        </a>

                        @if (! $notification->is_read)
                        <form action="{{ route('admin.notifications.read', $notification) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <button
                                type="submit"
                                class="inline-flex h-11 w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-black text-slate-700 transition hover:bg-slate-50">
                                Mark Read
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </x-admin.table-card>
        @empty
        <x-admin.table-card class="p-12 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                </svg>
            </div>

            <h3 class="mt-5 text-xl font-black text-slate-900">
                No notifications found
            </h3>

            <p class="mx-auto mt-2 max-w-xl text-sm font-semibold leading-6 text-slate-500">
                There are no notifications matching the selected filter. Try changing the status or type filter.
            </p>

            @if ($status !== 'all' || $type !== 'all')
            <a
                href="{{ route('admin.notifications.index') }}"
                class="mt-6 inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-black text-white transition hover:opacity-90">
                Reset Filter
            </a>
            @endif
        </x-admin.table-card>
        @endforelse
    </div>

    @if ($notifications->hasPages())
    <div>
        {{ $notifications->links() }}
    </div>
    @endif
</section>
@endsection