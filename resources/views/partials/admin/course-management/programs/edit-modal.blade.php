<x-admin.modal
    model="editModalOpen"
    title="Edit Course Program"
    subtitle="Update the selected course program."
    size="lg">
    <template x-if="selectedProgram">
        <form
            id="editCourseProgramForm"
            :action="selectedProgram.update_url"
            method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <x-admin.form.input
                label="Program Name"
                name="name"
                id="edit_name"
                x-model="selectedProgram.name"
                :required="true" />

            <x-admin.form.input
                label="Slug"
                name="slug"
                id="edit_slug"
                x-model="selectedProgram.slug" />

            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="edit_sort_order"
                type="number"
                min="0"
                x-model="selectedProgram.sort_order" />

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="edit_is_active"
                x-model="selectedProgram.is_active" />
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editCourseProgramForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>