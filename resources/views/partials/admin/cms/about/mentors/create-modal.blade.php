<x-admin.modal
    model="createModalOpen"
    title="Add Mentor"
    subtitle="Create a new mentor profile."
    size="lg">
    <form
        id="createMentorForm"
        action="{{ route('admin.cms.mentors.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <x-admin.form.input
            label="Name"
            name="name"
            id="create_name"
            :value="old('_form_type') === 'create' ? old('name') : ''"
            placeholder="Example: Sarah Johnson"
            :required="true" />

        <x-admin.form.file
            label="Photo"
            name="photo"
            id="create_photo"
            accept="image/*"
            hint="Recommended size: 600x600px. Max 2MB." />

        <x-admin.form.input
            label="Position"
            name="position"
            id="create_position"
            :value="old('_form_type') === 'create' ? old('position') : ''"
            placeholder="Example: Senior English Mentor" />

        <x-admin.form.input
            label="Expertise"
            name="expertise"
            id="create_expertise"
            :value="old('_form_type') === 'create' ? old('expertise') : ''"
            placeholder="Example: IELTS, TOEFL, Public Speaking" />

        <x-admin.form.textarea
            label="Description"
            name="description"
            id="create_description"
            :value="old('_form_type') === 'create' ? old('description') : ''"
            placeholder="Write a short mentor profile here..."
            rows="5" />

        <x-admin.form.input
            label="Sort Order"
            name="sort_order"
            id="create_sort_order"
            type="number"
            min="0"
            :value="old('_form_type') === 'create' ? old('sort_order', $nextSortOrder) : $nextSortOrder" />

        <x-admin.form.checkbox
            label="Active"
            name="is_active"
            id="create_is_active"
            :checked="old('_form_type') === 'create' ? (bool) old('is_active', true) : true" />
    </form>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="createModalOpen = false"
            submit-form="createMentorForm"
            submit-label="Save Mentor" />
    </x-slot:footer>
</x-admin.modal>