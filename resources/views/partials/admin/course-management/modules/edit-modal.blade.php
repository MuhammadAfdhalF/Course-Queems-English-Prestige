<x-admin.modal
    model="editModalOpen"
    title="Edit Module"
    subtitle="Update the selected learning module."
    size="xl">
    <template x-if="selectedModule">
        <form
            id="editModuleForm"
            :action="selectedModule.update_url"
            method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <div class="grid gap-6 md:grid-cols-2">
                <x-admin.form.input
                    label="Module Title"
                    name="title"
                    id="edit_title"
                    x-model="selectedModule.title"
                    :required="true" />

                <x-admin.form.input
                    label="Slug"
                    name="slug"
                    id="edit_slug"
                    x-model="selectedModule.slug" />
            </div>

            <x-admin.form.textarea
                label="Short Description"
                name="short_description"
                id="edit_short_description"
                x-model="selectedModule.short_description"
                rows="3" />

            <div class="grid gap-6 md:grid-cols-3">
                <x-admin.form.input
                    label="Sort Order"
                    name="sort_order"
                    id="edit_sort_order"
                    type="number"
                    min="0"
                    x-model="selectedModule.sort_order" />

                <div class="flex items-end">
                    <x-admin.form.checkbox
                        label="Preview Module"
                        name="is_preview"
                        id="edit_is_preview"
                        x-model="selectedModule.is_preview" />
                </div>

                <div class="flex items-end">
                    <x-admin.form.checkbox
                        label="Active"
                        name="is_active"
                        id="edit_is_active"
                        x-model="selectedModule.is_active" />
                </div>
            </div>
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editModuleForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>