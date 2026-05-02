<x-admin.page-header
    title="FAQ"
    description="Manage frequently asked questions displayed on the website.">
    <x-slot:actions>
        <button
            type="button"
            @click="createModalOpen = true"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Add FAQ</span>
        </button>
    </x-slot:actions>
</x-admin.page-header>