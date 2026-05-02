<x-admin.modal
    model="editModalOpen"
    title="Edit Why Choose Us Item"
    subtitle="Update the selected reason or advantage."
    size="lg">
    <template x-if="selectedWhyChooseUs">
        <form
            id="editWhyChooseUsForm"
            :action="selectedWhyChooseUs.update_url"
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
                x-model="selectedWhyChooseUs.title"
                :required="true" />

            <x-admin.form.textarea
                label="Description"
                name="description"
                id="edit_description"
                x-model="selectedWhyChooseUs.description"
                rows="5" />

            <div x-show="selectedWhyChooseUs.icon_url">
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Current Icon
                </label>

                <button
                    type="button"
                    @click="openImagePreview({
                        title: selectedWhyChooseUs.title,
                        url: selectedWhyChooseUs.icon_url
                    })"
                    class="group relative overflow-hidden rounded-xl">
                    <img
                        :src="selectedWhyChooseUs.icon_url"
                        :alt="selectedWhyChooseUs.title"
                        class="h-20 w-20 rounded-xl object-cover transition duration-200 group-hover:scale-105">

                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                        <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                    </span>
                </button>
            </div>

            <x-admin.form.file
                label="Replace Icon"
                name="icon"
                id="edit_icon"
                accept="image/*"
                hint="Leave empty if you do not want to replace the current icon." />

            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="edit_sort_order"
                type="number"
                min="0"
                x-model="selectedWhyChooseUs.sort_order" />

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="edit_is_active"
                x-model="selectedWhyChooseUs.is_active" />
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editWhyChooseUsForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>