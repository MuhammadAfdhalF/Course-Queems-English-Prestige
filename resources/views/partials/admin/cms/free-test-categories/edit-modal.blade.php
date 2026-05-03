<x-admin.modal
    model="editModalOpen"
    title="Edit Free Test Category"
    subtitle="Update the selected category."
    size="lg">
    <template x-if="selectedCategory">
        <form
            id="editFreeTestCategoryForm"
            :action="selectedCategory.update_url"
            method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <x-admin.form.input
                label="Name"
                name="name"
                id="edit_name"
                x-model="selectedCategory.name"
                :required="true" />

            <x-admin.form.input
                label="Slug"
                name="slug"
                id="edit_slug"
                x-model="selectedCategory.slug" />

            <x-admin.form.textarea
                label="Description"
                name="description"
                id="edit_description"
                x-model="selectedCategory.description"
                rows="4" />

            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="edit_sort_order"
                type="number"
                min="0"
                x-model="selectedCategory.sort_order" />

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="edit_is_active"
                x-model="selectedCategory.is_active" />
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editFreeTestCategoryForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>