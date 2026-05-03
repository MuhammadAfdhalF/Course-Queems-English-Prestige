<x-admin.page-toolbar
    :back-url="route('admin.cms.home.index')"
    back-label="Back to CMS Home">
    <x-slot:actions>
        <a
            href="{{ route('admin.cms.free-test-categories.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
            <x-admin.icon name="settings" class="h-5 w-5" />
            <span>Manage Categories</span>
        </a>

        <button
            type="button"
            @click="createModalOpen = true"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Add Free Test</span>
        </button>
    </x-slot:actions>
</x-admin.page-toolbar>