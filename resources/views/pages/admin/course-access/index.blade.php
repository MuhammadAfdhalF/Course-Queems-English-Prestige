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
                class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-black text-white shadow-sm transition hover:opacity-90">
                Grant Access
            </a>
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
                        Course Access
                    </p>

                    <h2 class="mt-3 text-3xl font-black leading-tight">
                        Student Course Access
                    </h2>

                    <p class="mt-3 max-w-3xl text-sm font-semibold leading-7 text-white/70">
                        Manage enrollments, manual access, learning progress, and course access status from one operational dashboard.
                    </p>
                </div>
            </div>

            <div class="grid gap-3 bg-white p-6 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-blue-500">Total</p>
                    <p class="mt-2 text-3xl font-black text-blue-700">{{ $totalAccess }}</p>
                    <p class="mt-1 text-xs font-semibold text-blue-500/70">All records</p>
                </div>

                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-emerald-500">Active</p>
                    <p class="mt-2 text-3xl font-black text-emerald-700">{{ $activeAccess }}</p>
                    <p class="mt-1 text-xs font-semibold text-emerald-500/70">Currently learning</p>
                </div>

                <div class="rounded-2xl border border-sky-100 bg-sky-50 px-4 py-3">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-sky-500">Completed</p>
                    <p class="mt-2 text-3xl font-black text-sky-700">{{ $completedAccess }}</p>
                    <p class="mt-1 text-xs font-semibold text-sky-500/70">Finished courses</p>
                </div>

                <div class="rounded-2xl border border-amber-100 bg-amber-50 px-4 py-3">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-amber-500">Manual</p>
                    <p class="mt-2 text-3xl font-black text-amber-700">{{ $manualAccess }}</p>
                    <p class="mt-1 text-xs font-semibold text-amber-500/70">Granted by admin</p>
                </div>
            </div>
        </div>
    </x-admin.table-card>

    <x-admin.table-card class="p-6">
        <form action="{{ route('admin.course-access.index') }}" method="GET">
            <div class="grid gap-4 xl:grid-cols-[1fr_180px_180px_220px_auto] xl:items-end">
                <div>
                    <label for="search" class="mb-2 block text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        Search
                    </label>

                    <input
                        id="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search student, email, or course..."
                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label for="status" class="mb-2 block text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="all" @selected($status==='all' )>All Status</option>
                        <option value="active" @selected($status==='active' )>Active</option>
                        <option value="completed" @selected($status==='completed' )>Completed</option>
                        <option value="cancelled" @selected($status==='cancelled' )>Cancelled</option>
                        <option value="expired" @selected($status==='expired' )>Expired</option>
                    </select>
                </div>

                <div>
                    <label for="source" class="mb-2 block text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        Source
                    </label>

                    <select
                        id="source"
                        name="source"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="all" @selected($source==='all' )>All Source</option>
                        <option value="order" @selected($source==='order' )>Order</option>
                        <option value="manual" @selected($source==='manual' )>Manual</option>
                    </select>
                </div>

                <div>
                    <label for="program" class="mb-2 block text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                        Program
                    </label>

                    <select
                        id="program"
                        name="program"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
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
                        class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-black text-white shadow-sm transition hover:opacity-90">
                        Filter
                    </button>

                    @if ($search || $status !== 'all' || $source !== 'all' || $program)
                    <a
                        href="{{ route('admin.course-access.index') }}"
                        class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-black text-slate-700 transition hover:bg-slate-50">
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