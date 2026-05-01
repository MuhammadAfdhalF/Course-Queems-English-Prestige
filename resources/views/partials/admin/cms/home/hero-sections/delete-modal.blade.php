<x-admin.modal
    model="deleteModalOpen"
    title="Delete Hero Section"
    subtitle="Data hero section yang dihapus tidak bisa dikembalikan."
    size="sm">
    <template x-if="selectedHero">
        <div class="space-y-4">
            <p class="text-sm leading-6 text-slate-600">
                Apakah kamu yakin ingin menghapus hero section:
            </p>

            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3">
                <p class="text-sm font-bold text-rose-700" x-text="selectedHero.title"></p>
            </div>

            <form
                id="deleteHeroSectionForm"
                :action="selectedHero.delete_url"
                method="POST">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </template>

    <x-slot:footer>
        <button
            type="button"
            @click="deleteModalOpen = false"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Cancel
        </button>

        <button
            type="submit"
            form="deleteHeroSectionForm"
            class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700">
            Delete
        </button>
    </x-slot:footer>
</x-admin.modal>