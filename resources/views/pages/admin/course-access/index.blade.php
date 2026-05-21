@extends('layouts.admin', [
'pageTitle' => 'Course Access',
'pageSubtitle' => 'Student Management',
])

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.dashboard')"
        back-label="Back to Dashboard">
        <x-slot:actions>
            <a
                href="{{ route('admin.course-access.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                Grant Access
            </a>
        </x-slot:actions>
    </x-admin.page-toolbar>

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="grid gap-5 lg:grid-cols-[1fr_auto] lg:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Course Access
                </p>

                <h2 class="mt-2 text-2xl font-extrabold text-slate-900">
                    Student Course Access
                </h2>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                    Manage student enrollments, manual course access, learning progress, and course access status.
                </p>
            </div>

            <div class="grid gap-3 sm:grid-cols-4">
                <div class="rounded-2xl bg-blue-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-blue-500">Total</p>
                    <p class="mt-1 text-2xl font-extrabold text-blue-700">{{ $totalAccess }}</p>
                </div>

                <div class="rounded-2xl bg-emerald-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-emerald-500">Active</p>
                    <p class="mt-1 text-2xl font-extrabold text-emerald-700">{{ $activeAccess }}</p>
                </div>

                <div class="rounded-2xl bg-sky-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-sky-500">Completed</p>
                    <p class="mt-1 text-2xl font-extrabold text-sky-700">{{ $completedAccess }}</p>
                </div>

                <div class="rounded-2xl bg-amber-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-amber-500">Manual</p>
                    <p class="mt-1 text-2xl font-extrabold text-amber-700">{{ $manualAccess }}</p>
                </div>
            </div>
        </div>
    </x-admin.table-card>

    <x-admin.table-card class="p-6">
        <form action="{{ route('admin.course-access.index') }}" method="GET">
            <div class="grid gap-4 xl:grid-cols-[1fr_180px_180px_220px_auto] xl:items-end">
                <div>
                    <label for="search" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Search
                    </label>

                    <input
                        id="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search student, email, or course..."
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label for="status" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="all" @selected($status==='all' )>All Status</option>
                        <option value="active" @selected($status==='active' )>Active</option>
                        <option value="completed" @selected($status==='completed' )>Completed</option>
                        <option value="cancelled" @selected($status==='cancelled' )>Cancelled</option>
                        <option value="expired" @selected($status==='expired' )>Expired</option>
                    </select>
                </div>

                <div>
                    <label for="source" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Source
                    </label>

                    <select
                        id="source"
                        name="source"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="all" @selected($source==='all' )>All Source</option>
                        <option value="order" @selected($source==='order' )>Order</option>
                        <option value="manual" @selected($source==='manual' )>Manual</option>
                    </select>
                </div>

                <div>
                    <label for="program" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Program
                    </label>

                    <select
                        id="program"
                        name="program"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">All Programs</option>

                        @foreach ($programs as $programItem)
                        <option value="{{ $programItem->slug }}" @selected($program===$programItem->slug)>
                            {{ $programItem->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                        Filter
                    </button>

                    @if ($search || $status !== 'all' || $source !== 'all' || $program)
                    <a
                        href="{{ route('admin.course-access.index') }}"
                        class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </x-admin.table-card>

    @include('partials.admin.course-access.table')
</section>
@endsection