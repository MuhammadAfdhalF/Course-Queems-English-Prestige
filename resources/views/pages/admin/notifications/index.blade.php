@extends('layouts.admin', [
'pageTitle' => 'Notifications',
'pageSubtitle' => 'Admin Center',
])

@section('content')
@php
$typeVariant = function (?string $type) {
return match ($type) {
'order' => 'pending',
'payment' => 'completed',
'practice' => 'approved',
'exam' => 'approved',
'certificate' => 'completed',
'testimonial' => 'approved',
default => 'default',
};
};
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
                    class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                    Mark All as Read
                </button>
            </form>
            @endif
        </x-slot:actions>
    </x-admin.page-toolbar>

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Notification Inbox
                </p>

                <h2 class="mt-2 text-2xl font-black text-slate-900">
                    Admin Notifications
                </h2>

                <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                    Track new orders, payment confirmations, rejected orders, and future admin activities.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                    Unread
                </p>

                <p class="mt-2 text-2xl font-black text-[var(--color-brand-blue)]">
                    {{ $unreadCount }}
                </p>
            </div>
        </div>
    </x-admin.table-card>

    <div class="space-y-4">
        @forelse ($notifications as $notification)
        <x-admin.table-card
            class="p-5 {{ $notification->is_read ? 'bg-white' : 'border-blue-200 bg-blue-50/30' }}">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-3">
                        <x-admin.status-badge :variant="$typeVariant($notification->type)">
                            {{ ucfirst($notification->type) }}
                        </x-admin.status-badge>

                        @if (! $notification->is_read)
                        <span class="inline-flex rounded-full bg-[var(--color-brand-gold)] px-3 py-1 text-xs font-bold text-white">
                            Unread
                        </span>
                        @else
                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                            Read
                        </span>
                        @endif
                    </div>

                    <h3 class="mt-4 text-lg font-black text-slate-900">
                        {{ $notification->title }}
                    </h3>

                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-600">
                        {{ $notification->message }}
                    </p>

                    <p class="mt-3 text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                        {{ $notification->created_at?->diffForHumans() }}
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col lg:items-stretch">
                    <a
                        href="{{ route('admin.notifications.open', $notification) }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white transition hover:opacity-90">
                        Open
                    </a>

                    @if (! $notification->is_read)
                    <form action="{{ route('admin.notifications.read', $notification) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <button
                            type="submit"
                            class="inline-flex h-11 w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                            Mark Read
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </x-admin.table-card>
        @empty
        <x-admin.table-card class="p-10 text-center">
            <h3 class="text-lg font-black text-slate-900">
                No notifications yet
            </h3>

            <p class="mx-auto mt-2 max-w-xl text-sm font-semibold leading-6 text-slate-500">
                New admin notifications will appear here after students create orders or admin processes payments.
            </p>
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