<x-admin.modal
    model="editModalOpen"
    title="Edit Hero Section"
    subtitle="Update the selected home page banner."
    size="lg">
    <template x-if="selectedHero">
        <form
            id="editHeroSectionForm"
            :action="selectedHero.update_url"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <x-admin.form.input
                label="Title"
                name="title"
                id="edit_title"
                x-model="selectedHero.title"
                :required="true" />

            <x-admin.form.input
                label="Subtitle"
                name="subtitle"
                id="edit_subtitle"
                x-model="selectedHero.subtitle" />

            <div x-show="selectedHero.image_url">
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Current Image
                </label>

                <button
                    type="button"
                    @click="openImagePreview({
                        title: selectedHero.title,
                        url: selectedHero.image_url
                    })"
                    class="group relative overflow-hidden rounded-xl">
                    <img
                        :src="selectedHero.image_url"
                        :alt="selectedHero.title"
                        class="h-28 w-48 rounded-xl object-cover transition duration-200 group-hover:scale-105">

                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                        <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                    </span>
                </button>
            </div>

            <x-admin.form.file
                label="Replace Image"
                name="image"
                id="edit_image"
                accept="image/*"
                hint="Leave empty if you do not want to replace the current image." />

            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="edit_sort_order"
                type="number"
                min="0"
                x-model="selectedHero.sort_order" />

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="edit_is_active"
                x-model="selectedHero.is_active" />
        </form>
    </template>

    <x-slot:footer>
        <button
            type="button"
            @click="editModalOpen = false"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Cancel
        </button>

        <button
            type="submit"
            form="editHeroSectionForm"
            class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            Save Changes
        </button>
    </x-slot:footer>
</x-admin.modal>