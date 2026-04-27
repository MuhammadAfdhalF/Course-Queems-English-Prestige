<x-admin.filter-bar class="items-center lg:grid-cols-[minmax(0,1fr)_auto_220px]">
    <div>
        <x-admin.filter-search
            x-model="search"
            placeholder="Search by name, email, or WhatsApp..." />
    </div>

    <div class="hidden items-center justify-end text-sm font-extrabold text-slate-700 lg:flex">
        Status:
    </div>

    <select
        x-model="statusFilter"
        class="h-13 rounded-xl border border-slate-200 bg-white px-4 text-sm font-extrabold text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">
        <option value="all">All Students</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>
</x-admin.filter-bar>