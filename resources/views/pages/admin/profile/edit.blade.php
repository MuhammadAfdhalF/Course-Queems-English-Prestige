@extends('layouts.admin', [
'pageTitle' => 'Admin Profile',
'pageSubtitle' => 'Account Settings',
])

@section('content')
@php
$adminName = $admin?->name ?? 'Administrator';

$adminInitials = collect(explode(' ', trim($adminName)))
->filter()
->take(2)
->map(fn ($word) => strtoupper(substr($word, 0, 1)))
->implode('');

$adminInitials = $adminInitials ?: 'A';
@endphp

<section class="mx-auto max-w-6xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.dashboard')"
        back-label="Back to Dashboard" />

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Account Settings
                </p>

                <h1 class="mt-2 text-3xl font-black text-slate-900">
                    Admin Profile
                </h1>

                <p class="mt-2 max-w-3xl text-sm font-semibold leading-6 text-slate-500">
                    Manage your admin name and password. Email, role, and account status are kept readonly for security.
                </p>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-lg font-black text-amber-700">
                    {{ $adminInitials }}
                </div>

                <div>
                    <p class="text-sm font-black text-slate-900">
                        {{ $admin->name }}
                    </p>

                    <p class="mt-1 text-xs font-semibold text-slate-500">
                        {{ $admin->email }}
                    </p>
                </div>
            </div>
        </div>
    </x-admin.table-card>

    <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
        <div class="space-y-6">
            <x-admin.table-card class="p-6">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Profile Information
                </p>

                <h2 class="mt-2 text-xl font-black text-slate-900">
                    Basic Account Data
                </h2>

                <form
                    action="{{ route('admin.profile.update') }}"
                    method="POST"
                    class="mt-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                            Name
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $admin->name) }}"
                            required
                            class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">

                        @error('name')
                        <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            value="{{ $admin->email }}"
                            readonly
                            class="h-12 w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-500">

                        <p class="mt-2 text-xs font-semibold text-slate-400">
                            Email is used for login and cannot be changed from this page.
                        </p>
                    </div>

                    <div class="flex justify-end border-t border-slate-100 pt-5">
                        <button
                            type="submit"
                            class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                            Save Profile
                        </button>
                    </div>
                </form>
            </x-admin.table-card>

            <x-admin.table-card class="p-6">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Security
                </p>

                <h2 class="mt-2 text-xl font-black text-slate-900">
                    Change Password
                </h2>

                <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                    Use a strong password with at least 8 characters.
                </p>

                <form
                    action="{{ route('admin.profile.password.update') }}"
                    method="POST"
                    class="mt-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                            Current Password
                        </label>

                        <input
                            id="current_password"
                            name="current_password"
                            type="password"
                            required
                            autocomplete="current-password"
                            class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">

                        @error('current_password')
                        <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                            New Password
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">

                        @error('password')
                        <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                            Confirm New Password
                        </label>

                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div class="flex justify-end border-t border-slate-100 pt-5">
                        <button
                            type="submit"
                            class="inline-flex h-12 items-center justify-center rounded-xl bg-slate-900 px-6 text-sm font-bold text-white shadow-sm transition hover:bg-slate-800">
                            Update Password
                        </button>
                    </div>
                </form>
            </x-admin.table-card>
        </div>

        <aside class="space-y-6">
            <x-admin.table-card class="p-6">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Account Summary
                </p>

                <div class="mt-5 flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-amber-100 text-lg font-black text-amber-700">
                        {{ $adminInitials }}
                    </div>

                    <div class="min-w-0">
                        <h2 class="break-words text-lg font-black text-slate-900">
                            {{ $admin->name }}
                        </h2>

                        <p class="mt-1 break-all text-sm font-semibold text-slate-500">
                            {{ $admin->email }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            Role
                        </p>

                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ ucfirst($admin->role) }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            Status
                        </p>

                        <div class="mt-2">
                            <x-admin.status-badge :variant="$admin->is_active ? 'completed' : 'rejected'">
                                {{ $admin->is_active ? 'Active' : 'Inactive' }}
                            </x-admin.status-badge>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            Joined
                        </p>

                        <p class="mt-2 text-sm font-black text-slate-900">
                            {{ $admin->created_at?->format('d F Y') ?? '-' }}
                        </p>
                    </div>
                </div>
            </x-admin.table-card>

            <x-admin.table-card class="border-blue-100 bg-blue-50 p-6">
                <h3 class="text-base font-black text-[var(--color-brand-blue)]">
                    Security Note
                </h3>

                <p class="mt-2 text-sm font-semibold leading-6 text-blue-700">
                    For safety, email, role, and account status are not editable from this page. Password changes require your current password.
                </p>
            </x-admin.table-card>
        </aside>
    </div>
</section>
@endsection