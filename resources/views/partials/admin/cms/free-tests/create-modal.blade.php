<x-admin.modal
    model="createModalOpen"
    title="Add Free Test"
    subtitle="Create a free multiple-choice test."
    size="lg">
    <form
        id="createFreeTestForm"
        action="{{ route('admin.cms.free-tests.store') }}"
        method="POST"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <x-admin.form.input
            label="Title"
            name="title"
            id="create_title"
            :value="old('_form_type') === 'create' ? old('title') : ''"
            placeholder="Example: Grammar Placement Test"
            :required="true" />

        <x-admin.form.select
            label="Category"
            name="free_test_category_id"
            id="create_free_test_category_id"
            :value="old('_form_type') === 'create' ? old('free_test_category_id') : ''"
            :options="$categories->pluck('name', 'id')->toArray()"
            placeholder="Select category" />

        <x-admin.form.textarea
            label="Description"
            name="description"
            id="create_description"
            :value="old('_form_type') === 'create' ? old('description') : ''"
            placeholder="Write a short description for this free test..."
            rows="4" />

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.input
                label="Duration Minutes"
                name="duration_minutes"
                id="create_duration_minutes"
                type="number"
                min="1"
                :value="old('_form_type') === 'create' ? old('duration_minutes') : ''"
                placeholder="Example: 30" />

            <x-admin.form.input
                label="Passing Grade (%)"
                name="passing_grade"
                id="create_passing_grade"
                type="number"
                min="0"
                max="100"
                :value="old('_form_type') === 'create' ? old('passing_grade') : ''"
                placeholder="Example: 70" />
        </div>

        <x-admin.form.checkbox
            label="Active"
            name="is_active"
            id="create_is_active"
            :checked="old('_form_type') === 'create' ? (bool) old('is_active', true) : true" />
    </form>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="createModalOpen = false"
            submit-form="createFreeTestForm"
            submit-label="Save Free Test" />
    </x-slot:footer>
</x-admin.modal>