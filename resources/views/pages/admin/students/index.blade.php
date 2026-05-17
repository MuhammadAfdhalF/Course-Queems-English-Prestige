@extends('layouts.admin', [
'pageTitle' => 'Students',
'pageSubtitle' => 'Student Management',
])

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.dashboard')"
        back-label="Back to Dashboard" />

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="grid gap-5 lg:grid-cols-[1fr_auto] lg:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Student Management
                </p>

                <h2 class="mt-2 text-2xl font-extrabold text-slate-900">
                    Registered Students
                </h2>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                    View student accounts, profile information, course access, orders, certificates, testimonials, and assessment summaries.
                </p>
            </div>

            <div class="grid gap-3 sm:grid-cols-3">
                <div class="rounded-2xl bg-blue-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-blue-500">
                        Total
                    </p>
                    <p class="mt-1 text-2xl font-extrabold text-blue-700">
                        {{ $totalStudents }}
                    </p>
                </div>

                <div class="rounded-2xl bg-emerald-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-emerald-500">
                        Active
                    </p>
                    <p class="mt-1 text-2xl font-extrabold text-emerald-700">
                        {{ $activeStudents }}
                    </p>
                </div>

                <div class="rounded-2xl bg-rose-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-rose-500">
                        Inactive
                    </p>
                    <p class="mt-1 text-2xl font-extrabold text-rose-700">
                        {{ $inactiveStudents }}
                    </p>
                </div>
            </div>
        </div>
    </x-admin.table-card>

    <x-admin.table-card class="p-6">
        <form action="{{ route('admin.students.index') }}" method="GET">
            <div class="grid gap-4 lg:grid-cols-[1fr_220px_auto] lg:items-end">
                <div>
                    <label for="search" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Search Student
                    </label>

                    <input
                        id="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search by name, email, or WhatsApp..."
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
                        <option value="inactive" @selected($status==='inactive' )>Inactive</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                        Filter
                    </button>

                    @if ($search || $status !== 'all')
                    <a
                        href="{{ route('admin.students.index') }}"
                        class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </x-admin.table-card>

    @include('partials.admin.students.table')
</section>
@endsection