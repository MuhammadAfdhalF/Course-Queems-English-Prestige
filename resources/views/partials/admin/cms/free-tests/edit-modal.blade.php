<x-admin.modal
    model="editModalOpen"
    title="Edit Free Test"
    subtitle="Update the selected free test."
    size="lg">
    <template x-if="selectedFreeTest">
        <form
            id="editFreeTestForm"
            :action="selectedFreeTest.update_url"
            method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <x-admin.form.input
                label="Title"
                name="title"
                id="edit_title"
                x-model="selectedFreeTest.title"
                :required="true" />

            <x-admin.form.select
                label="Category"
                name="free_test_category_id"
                id="edit_free_test_category_id"
                x-model="selectedFreeTest.free_test_category_id"
                :options="$categories->pluck('name', 'id')->toArray()"
                placeholder="Select category" />

            <x-admin.form.textarea
                label="Description"
                name="description"
                id="edit_description"
                x-model="selectedFreeTest.description"
                rows="4" />

            <div class="grid gap-6 md:grid-cols-2">
                <x-admin.form.input
                    label="Duration Minutes"
                    name="duration_minutes"
                    id="edit_duration_minutes"
                    type="number"
                    min="1"
                    x-model="selectedFreeTest.duration_minutes" />

                <x-admin.form.input
                    label="Passing Grade (%)"
                    name="passing_grade"
                    id="edit_passing_grade"
                    type="number"
                    min="0"
                    max="100"
                    x-model="selectedFreeTest.passing_grade" />
            </div>

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="edit_is_active"
                x-model="selectedFreeTest.is_active" />
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editFreeTestForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>