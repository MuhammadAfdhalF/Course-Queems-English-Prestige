@extends('layouts.student', [
'title' => 'Notifications - Student Area',
])

@section('content')
@php
$typeLabel = function (?string $type) {
return match ($type) {
'practice_reviewed' => 'Practice',
'final_exam_reviewed' => 'Final Exam',
'certificate_ready' => 'Certificate',
default => ucfirst(str_replace('_', ' ', (string) $type)),
};
};

$typeTone = function (?string $type) {
return match ($type) {
'practice_reviewed' => [
'badge' => 'bg-blue-100 text-blue-700',
'icon' => 'bg-blue-50 text-blue-700',
'border' => 'border-blue-100',
],
'final_exam_reviewed' => [
'badge' => 'bg-amber-100 text-amber-700',
'icon' => 'bg-amber-50 text-amber-700',
'border' => 'border-amber-100',
],
'certificate_ready' => [
'badge' => 'bg-emerald-100 text-emerald-700',
'icon' => 'bg-emerald-50 text-emerald-700',
'border' => 'border-emerald-100',
],
default => [
'badge' => 'bg-slate-100 text-slate-700',
'icon' => 'bg-slate-50 text-slate-700',
'border' => 'border-slate-100',
],
};
};

$activeFilter = 'bg-[#080D4D] text-white border-[#080D4D]';
$inactiveFilter = 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50';
@endphp

<section class="mx-auto max-w-6xl space-y-6">
    <div class="overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-sm">
        <div class="grid gap-0 lg:grid-cols-[1fr_auto]">
            <div class="relative overflow-hidden bg-gradient-to-br from-[#080D4D] via-[#101C72] to-[#AD6B10] p-7 text-white">
                <div class="pointer-events-none absolute -right-16 -top-16 h-44 w-44 rounded-full bg-white/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-20 left-20 h-44 w-44 rounded-full bg-[#AD6B10]/30 blur-3xl"></div>

                <div class="relative">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-white/60">
                        Student Inbox
                    </p>

                    <h1 class="mt-3 text-3xl font-black leading-tight">
                        Notifications
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm font-semibold leading-7 text-white/80">
                        Stay updated with your practice reviews, final exam results, and certificate availability.
                    </p>
                </div>
            </div>

            <div class="grid gap-3 bg-white p-6 sm:grid-cols-3">
                <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.16em] text-blue-500">Total</p>
                    <p class="mt-2 text-3xl font-black text-blue-700">{{ $totalCount }}</p>
                </div>

                <div class="rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.16em] text-amber-500">Unread</p>
                    <p class="mt-2 text-3xl font-black text-amber-700">{{ $unreadCount }}</p>
                </div>

                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.16em] text-emerald-500">Read</p>
                    <p class="mt-2 text-3xl font-black text-emerald-700">{{ $readCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                    Filter
                </p>

                <div class="mt-3 flex flex-wrap gap-2">
                    <a
                        href="{{ route('student.notifications.index', ['status' => 'all']) }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border px-4 text-sm font-black transition {{ $status === 'all' ? $activeFilter : $inactiveFilter }}">
                        All
                    </a>

                    <a
                        href="{{ route('student.notifications.index', ['status' => 'unread']) }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border px-4 text-sm font-black transition {{ $status === 'unread' ? $activeFilter : $inactiveFilter }}">
                        Unread
                    </a>

                    <a
                        href="{{ route('student.notifications.index', ['status' => 'read']) }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border px-4 text-sm font-black transition {{ $status === 'read' ? $activeFilter : $inactiveFilter }}">
                        Read
                    </a>
                </div>
            </div>

            @if ($unreadCount > 0)
            <form action="{{ route('student.notifications.read-all') }}" method="POST">
                @csrf
                @method('PUT')

                <button
                    type="submit"
                    class="inline-flex h-11 items-center justify-center rounded-xl bg-[#080D4D] px-5 text-sm font-black text-white transition hover:bg-[#AD6B10]">
                    Mark All as Read
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="space-y-4">
        @forelse ($notifications as $notification)
        @php
        $tone = $typeTone($notification->type);
        @endphp

        <div class="overflow-hidden rounded-[24px] border shadow-sm {{ $notification->is_read ? 'border-slate-200 bg-white' : 'border-blue-200 bg-blue-50/40' }}">
            <div class="p-5">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex min-w-0 flex-1 gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border {{ $tone['icon'] }} {{ $tone['border'] }}">
                            @if ($notification->type === 'certificate_ready')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="5" y="3" width="14" height="18" rx="2" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 8h6M9 12h6M9 16h3" />
                            </svg>
                            @elseif ($notification->type === 'final_exam_reviewed')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4h10a2 2 0 012 2v14l-3-2-3 2-3-2-3 2V6a2 2 0 012-2zM9 9h6M9 13h6" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-3-2-3 2-3-2-3 2V6a2 2 0 012-2z" />
                            </svg>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-black {{ $tone['badge'] }}">
                                    {{ $typeLabel($notification->type) }}
                                </span>

                                @if (! $notification->is_read)
                                <span class="inline-flex rounded-full bg-[#AD6B10] px-3 py-1 text-xs font-black text-white">
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

                    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                        <a
                            href="{{ route('student.notifications.open', $notification) }}"
                            class="inline-flex h-11 items-center justify-center rounded-xl bg-[#080D4D] px-5 text-sm font-black text-white transition hover:bg-[#AD6B10]">
                            Open
                        </a>

                        @if (! $notification->is_read)
                        <form action="{{ route('student.notifications.read', $notification) }}" method="POST">
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
        </div>
        @empty
        <div class="rounded-[24px] border border-slate-200 bg-white p-12 text-center shadow-sm">
            <h3 class="text-xl font-black text-slate-900">
                No notifications found
            </h3>

            <p class="mx-auto mt-2 max-w-xl text-sm font-semibold leading-6 text-slate-500">
                Your practice results, final exam updates, and certificates will appear here.
            </p>
        </div>
        @endforelse
    </div>

    @if ($notifications->hasPages())
    <div>
        {{ $notifications->links() }}
    </div>
    @endif
</section>
@endsection