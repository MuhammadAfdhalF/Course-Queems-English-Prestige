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

$typeBadgeVariant = function (?string $type) {
    return match ($type) {
        'order' => 'primary',
        'payment' => 'approved',
        'practice_review' => 'primary',
        'final_exam_review' => 'warning',
        'testimonial' => 'default',
        default => 'default',
    };
};

$activeStatusClass = 'bg-[#080D4D] text-white border-[#080D4D]';
$inactiveStatusClass = 'bg-white text-slate-700 border-slate-200/90 hover:bg-slate-50';
@endphp

<section class="mx-auto max-w-6xl space-y-5 sm:space-y-6">
    <x-admin.page-header
        title="Admin Notifications"
        description="Monitor orders, payments, student submissions, review requests, and testimonials from one inbox."
        eyebrow="Notification Inbox">
        <x-slot:actions>
            <x-admin.button href="{{ route('admin.dashboard') }}" variant="outline" size="sm">
                Back to Dashboard
            </x-admin.button>

            @if ($unreadCount > 0)
            <form action="{{ route('admin.notifications.read-all') }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <x-admin.button type="submit" variant="primary" size="sm">
                    Mark All as Read
                </x-admin.button>
            </form>
            @endif
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.flash-message />

    {{-- Notification Stats Summary --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200/90 bg-white p-4 shadow-2xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                Total Inbox
            </p>
            <p class="mt-1 text-2xl sm:text-3xl font-black text-[#080D4D]">
                {{ $totalCount }}
            </p>
            <p class="mt-0.5 text-[11px] font-medium text-slate-400">
                All notifications
            </p>
        </div>

        <div class="rounded-xl border border-amber-200/80 bg-white p-4 shadow-2xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#AD6B10]">
                Unread
            </p>
            <p class="mt-1 text-2xl sm:text-3xl font-black text-[#AD6B10]">
                {{ $unreadCount }}
            </p>
            <p class="mt-0.5 text-[11px] font-medium text-[#AD6B10]">
                Needs attention
            </p>
        </div>

        <div class="rounded-xl border border-slate-200/90 bg-white p-4 shadow-2xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                Read Status
            </p>
            <p class="mt-1 text-2xl sm:text-3xl font-black text-slate-700">
                {{ $readCount }}
            </p>
            <p class="mt-0.5 text-[11px] font-medium text-slate-400">
                Already checked
            </p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <x-admin.table-card class="p-4 sm:p-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-slate-500 mr-1">Filter Status:</span>
                <a
                    href="{{ route('admin.notifications.index', ['status' => 'all', 'type' => $type]) }}"
                    class="inline-flex h-8 items-center rounded-lg border px-3 text-xs font-bold transition {{ $status === 'all' ? $activeStatusClass : $inactiveStatusClass }}">
                    All
                </a>

                <a
                    href="{{ route('admin.notifications.index', ['status' => 'unread', 'type' => $type]) }}"
                    class="inline-flex h-8 items-center rounded-lg border px-3 text-xs font-bold transition {{ $status === 'unread' ? $activeStatusClass : $inactiveStatusClass }}">
                    Unread
                </a>

                <a
                    href="{{ route('admin.notifications.index', ['status' => 'read', 'type' => $type]) }}"
                    class="inline-flex h-8 items-center rounded-lg border px-3 text-xs font-bold transition {{ $status === 'read' ? $activeStatusClass : $inactiveStatusClass }}">
                    Read
                </a>
            </div>

            <form action="{{ route('admin.notifications.index') }}" method="GET" class="flex items-center gap-2.5">
                <input type="hidden" name="status" value="{{ $status }}">

                <div class="min-w-[180px]">
                    <select
                        id="type"
                        name="type"
                        class="h-8 w-full rounded-lg border border-slate-200/90 bg-white px-3 text-xs font-bold text-slate-700 outline-none transition focus:border-[#080D4D] focus:ring-2 focus:ring-[#080D4D]/10">
                        @foreach ($typeOptions as $typeValue => $typeName)
                        <option value="{{ $typeValue }}" @selected($type===$typeValue)>
                            {{ $typeName }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <x-admin.button type="submit" variant="primary" size="sm">
                    Apply
                </x-admin.button>

                @if ($status !== 'all' || $type !== 'all')
                <x-admin.button href="{{ route('admin.notifications.index') }}" variant="outline" size="sm">
                    Reset
                </x-admin.button>
                @endif
            </form>
        </div>
    </x-admin.table-card>

    {{-- Notification Item List --}}
    <div class="space-y-3">
        @forelse ($notifications as $notification)
        <x-admin.table-card
            class="transition hover:border-slate-300 {{ $notification->is_read ? 'bg-white' : 'border-amber-200/80 bg-amber-50/20' }}">
            <div class="p-4 sm:p-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex min-w-0 flex-1 gap-3.5">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#080D4D]/5 text-[#080D4D]">
                            @if ($notification->type === 'payment')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8h18M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2zM7 15h4" />
                            </svg>
                            @elseif ($notification->type === 'practice_review')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-3-2-3 2-3-2-3 2V6a2 2 0 012-2z" />
                            </svg>
                            @elseif ($notification->type === 'final_exam_review')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4h10a2 2 0 012 2v14l-3-2-3 2-3-2-3 2V6a2 2 0 012-2zM9 9h6M9 13h6" />
                            </svg>
                            @elseif ($notification->type === 'testimonial')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 5a3 3 0 016 0" />
                            </svg>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <x-admin.status-badge :variant="$typeBadgeVariant($notification->type)" size="sm">
                                    {{ $typeLabel($notification->type) }}
                                </x-admin.status-badge>

                                @if (! $notification->is_read)
                                <x-admin.status-badge variant="pending" size="sm">
                                    Unread
                                </x-admin.status-badge>
                                @else
                                <x-admin.status-badge variant="default" size="sm">
                                    Read
                                </x-admin.status-badge>
                                @endif

                                <span class="text-[11px] font-medium text-slate-400">
                                    {{ $notification->created_at?->diffForHumans() }}
                                </span>
                            </div>

                            <h3 class="mt-1.5 text-sm font-bold text-slate-900">
                                {{ $notification->title }}
                            </h3>

                            <p class="mt-1 text-xs font-medium leading-relaxed text-slate-600">
                                {{ $notification->message }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <x-admin.button href="{{ route('admin.notifications.open', $notification) }}" variant="primary" size="sm">
                            Open
                        </x-admin.button>

                        @if (! $notification->is_read)
                        <form action="{{ route('admin.notifications.read', $notification) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <x-admin.button type="submit" variant="outline" size="sm">
                                Mark Read
                            </x-admin.button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </x-admin.table-card>
        @empty
        <x-admin.empty-state
            title="No notifications found"
            description="There are no notifications matching the selected filter. Try changing the status or type filter.">
            @if ($status !== 'all' || $type !== 'all')
            <x-admin.button href="{{ route('admin.notifications.index') }}" variant="primary" size="sm">
                Reset Filter
            </x-admin.button>
            @endif
        </x-admin.empty-state>
        @endforelse
    </div>

    @if ($notifications->hasPages())
    <div>
        {{ $notifications->links() }}
    </div>
    @endif
</section>
@endsection