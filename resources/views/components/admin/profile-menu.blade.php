@php
$admin = auth()->user();
$adminName = $admin?->name ?? 'Administrator';
$adminInitials = collect(explode(' ', trim($adminName)))
->filter()
->take(2)
->map(fn ($word) => strtoupper(substr($word, 0, 1)))
->implode('');

$adminInitials = $adminInitials ?: 'A';
@endphp

<div x-data="{ open: false }" class="relative">
    <button
        @click="open = !open"
        type="button"
        class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 transition hover:shadow-sm">
        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-amber-100 text-sm font-extrabold uppercase text-amber-700">
            {{ $adminInitials }}
        </div>

        <div class="hidden text-right sm:block">
            <p class="max-w-[170px] truncate text-sm font-extrabold text-slate-900">
                {{ $adminName }}
            </p>

            <p class="text-xs font-semibold text-slate-500">
                Head Administrator
            </p>
        </div>

        <svg xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4 text-slate-400 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="open"
        @click.outside="open = false"
        x-transition
        class="absolute right-0 z-50 mt-2 w-56 rounded-2xl border border-slate-200 bg-white p-2 shadow-lg">
        <a
            href="{{ route('admin.profile.edit') }}"
            class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Profile
        </a>

        <a
            href="{{ route('admin.profile.edit') }}"
            class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Settings
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button
                type="submit"
                class="block w-full rounded-xl px-4 py-3 text-left text-sm font-semibold text-rose-600 transition hover:bg-rose-50">
                Logout
            </button>
        </form>
    </div>
</div>