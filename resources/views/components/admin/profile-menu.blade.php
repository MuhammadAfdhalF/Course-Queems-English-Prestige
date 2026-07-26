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
        class="flex items-center gap-2.5 rounded-xl border border-slate-200/90 bg-white p-1.5 pr-3 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#080D4D]/20">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#080D4D] text-xs font-bold text-white shadow-xs">
            {{ $adminInitials }}
        </div>

        <div class="hidden text-left sm:block">
            <p class="max-w-[140px] truncate text-xs font-bold text-slate-900 leading-tight">
                {{ $adminName }}
            </p>
            <p class="text-[10px] font-semibold text-slate-500">
                Administrator
            </p>
        </div>

        <svg xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4 text-slate-400 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="open"
        @click.outside="open = false"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 z-50 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl">

        <div class="border-b border-slate-100 px-3 py-2 sm:hidden">
            <p class="truncate text-xs font-bold text-slate-900">{{ $adminName }}</p>
            <p class="text-[10px] font-semibold text-slate-500">Administrator</p>
        </div>

        <a
            href="{{ route('admin.profile.edit') }}"
            class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-[#080D4D]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile Settings
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button
                type="submit"
                class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-semibold text-rose-600 transition hover:bg-rose-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>