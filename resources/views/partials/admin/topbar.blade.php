<header class="sticky top-0 z-30 flex h-[76px] shrink-0 items-center border-b border-slate-200/90 bg-white/95 backdrop-blur-md transition-all">
    <div class="flex flex-1 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        {{-- Left Section: Navigation Toggles & Page Title --}}
        <div class="flex min-w-0 items-center gap-3">
            {{-- Mobile Off-Canvas Drawer Toggle --}}
            <button
                @click="toggleMobile()"
                type="button"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-xs transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#080D4D]/20 lg:hidden"
                aria-label="Open mobile menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- Desktop Collapse/Expand Sidebar Toggle --}}
            <button
                @click="toggleDesktop()"
                type="button"
                class="hidden h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-xs transition hover:bg-slate-50 hover:text-[#080D4D] focus:outline-none focus:ring-2 focus:ring-[#080D4D]/20 lg:inline-flex"
                :title="desktopCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'"
                :aria-label="desktopCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 transition-transform duration-300"
                    :class="desktopCollapsed ? 'rotate-180' : ''"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>

            {{-- Page Subtitle & Title --}}
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    {{ $pageSubtitle ?? 'Admin Panel' }}
                </p>
                <h1 class="mt-0.5 truncate text-lg font-bold text-slate-900 sm:text-xl">
                    {{ $pageTitle ?? 'Dashboard' }}
                </h1>
            </div>
        </div>

        {{-- Right Section: Search, Notifications & Profile Menu --}}
        <div class="flex items-center gap-2 sm:gap-4">
            {{-- Search Bar --}}
            <x-admin.topbar-search />

            {{-- Notifications Button & Badge --}}
            @php
            $adminUnreadNotifications = auth()->check()
                ? auth()->user()->notifications()->where('is_read', false)->count()
                : 0;
            @endphp

            <a
                href="{{ route('admin.notifications.index') }}"
                class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200/80 bg-slate-50/80 text-slate-600 transition hover:bg-slate-100 hover:text-[#080D4D] focus:outline-none focus:ring-2 focus:ring-[#080D4D]/20"
                aria-label="Notifications">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                </svg>

                @if ($adminUnreadNotifications > 0)
                <span class="absolute -right-1 -top-1 inline-flex h-4 min-w-[16px] items-center justify-center rounded-full bg-[#AD6B10] px-1 text-[10px] font-extrabold text-white shadow-xs">
                    {{ $adminUnreadNotifications > 99 ? '99+' : $adminUnreadNotifications }}
                </span>
                @endif
            </a>

            {{-- Profile Dropdown Menu --}}
            <x-admin.profile-menu />
        </div>
    </div>
</header>