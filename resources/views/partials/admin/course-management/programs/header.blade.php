<div class="space-y-5">
    {{-- Page Toolbar & Add Button --}}
    <x-admin.page-toolbar
        :back-url="route('admin.dashboard')"
        back-label="Back to Dashboard">
        <x-slot:actions>
            <button
                type="button"
                @click="createModalOpen = true"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                <x-admin.icon name="plus" class="h-5 w-5" />
                <span>Add Course Program</span>
            </button>
        </x-slot:actions>
    </x-admin.page-toolbar>

    {{-- Summary Stats Pills / Cards --}}
    <div class="grid gap-3 sm:grid-cols-3">
        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Programs</p>
                <p class="mt-1 text-2xl font-black text-slate-800">{{ $totalPrograms }}</p>
            </div>
            <span class="rounded-xl bg-blue-50 p-2.5 text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </span>
        </div>

        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Programs</p>
                <p class="mt-1 text-2xl font-black text-emerald-700">{{ $activePrograms }}</p>
            </div>
            <span class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
        </div>

        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Levels</p>
                <p class="mt-1 text-2xl font-black text-amber-700">{{ $totalLevels }}</p>
            </div>
            <span class="rounded-xl bg-amber-50 p-2.5 text-amber-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </span>
        </div>
    </div>

    {{-- Utility Bar: Search & Status Filter Pills --}}
    <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        {{-- Search Input --}}
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                type="text"
                x-model="searchQuery"
                placeholder="Search program by name or slug..."
                class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 transition focus:border-[var(--color-brand-blue)] focus:bg-white focus:outline-none">
        </div>

        {{-- Status Filter Tabs --}}
        <div class="flex items-center gap-1.5 self-start sm:self-auto">
            <button
                type="button"
                @click="statusFilter = 'all'"
                :class="statusFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                class="rounded-lg px-3 py-1.5 text-xs font-bold transition">
                All
            </button>

            <button
                type="button"
                @click="statusFilter = 'active'"
                :class="statusFilter === 'active' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                class="rounded-lg px-3 py-1.5 text-xs font-bold transition">
                Active
            </button>

            <button
                type="button"
                @click="statusFilter = 'inactive'"
                :class="statusFilter === 'inactive' ? 'bg-slate-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                class="rounded-lg px-3 py-1.5 text-xs font-bold transition">
                Inactive
            </button>
        </div>
    </div>
</div>