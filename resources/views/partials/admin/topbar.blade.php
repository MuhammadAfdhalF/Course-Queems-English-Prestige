<header class="border-b border-slate-200 bg-white">
    <div class="flex items-center justify-between gap-4 px-4 py-4 lg:px-8">
        <div class="flex items-center gap-3">
            <button
                @click="adminSidebarOpen = true"
                type="button"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 p-2 text-slate-700 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div>
                <p class="text-sm font-medium text-slate-400">Dashboard</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Dashboard</h1>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-admin.topbar-search />

            <button class="relative rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                </svg>
                <span class="absolute right-1 top-1 inline-flex h-4 min-w-[16px] items-center justify-center rounded-full bg-[#D4A017] px-1 text-[10px] font-bold text-white">
                    3
                </span>
            </button>

            <x-admin.profile-menu />
        </div>
    </div>
</header>