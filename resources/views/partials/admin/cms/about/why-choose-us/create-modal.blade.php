<x-admin.modal
    model="createModalOpen"
    title="Add Why Choose Us Item"
    subtitle="Create a new reason or advantage."
    size="lg">
    <form
        id="createWhyChooseUsForm"
        action="{{ route('admin.cms.why-choose-us.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <x-admin.form.input
            label="Title"
            name="title"
            id="create_title"
            :value="old('_form_type') === 'create' ? old('title') : ''"
            placeholder="Example: Experienced Mentors"
            :required="true" />

        <x-admin.form.textarea
            label="Description"
            name="description"
            id="create_description"
            :value="old('_form_type') === 'create' ? old('description') : ''"
            placeholder="Write a short explanation here..."
            rows="5" />

        <x-admin.form.file
            label="Icon"
            name="icon"
            id="create_icon"
            accept="image/*"
            hint="Upload a small icon or image. Max 2MB." />

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
            submit-form="createWhyChooseUsForm"
            submit-label="Save Item" />
    </x-slot:footer>
</x-admin.modal>