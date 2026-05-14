@extends('layouts.admin', [
'pageTitle' => 'Issued Certificates',
'pageSubtitle' => 'Certificate Management',
])

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.dashboard')"
        back-label="Back to Dashboard" />

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Certificate Management
                </p>

                <h2 class="mt-2 text-2xl font-bold text-slate-900">
                    Student Certificates
                </h2>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                    Review issued, locked, and revoked certificates. Admin can download issued certificates, revoke active certificates, or re-issue revoked certificates.
                </p>
            </div>

            <div class="grid gap-3 sm:grid-cols-3">
                <div class="rounded-2xl bg-amber-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-amber-500">
                        Locked
                    </p>
                    <p class="mt-1 text-2xl font-extrabold text-amber-700">
                        {{ $lockedCount }}
                    </p>
                </div>

                <div class="rounded-2xl bg-emerald-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-emerald-500">
                        Issued
                    </p>
                    <p class="mt-1 text-2xl font-extrabold text-emerald-700">
                        {{ $issuedCount }}
                    </p>
                </div>

                <div class="rounded-2xl bg-rose-50 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-rose-500">
                        Revoked
                    </p>
                    <p class="mt-1 text-2xl font-extrabold text-rose-700">
                        {{ $revokedCount }}
                    </p>
                </div>
            </div>
        </div>
    </x-admin.table-card>

    @include('partials.admin.course-management.certificates.table')
</section>
@endsection