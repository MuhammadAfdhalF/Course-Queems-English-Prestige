<x-admin.modal
    model="createModalOpen"
    title="Add Free Test Category"
    subtitle="Create a category for free tests."
    size="lg">
    <form
        id="createFreeTestCategoryForm"
        x-data="{
            name: @js(old('_form_type') === 'create' ? old('name', '') : ''),
            slug: @js(old('_form_type') === 'create' ? old('slug', '') : ''),
            autoSlug: {{ old('_form_type') === 'create' && old('slug') ? 'false' : 'true' }},

            syncSlug() {
                if (this.autoSlug) {
                    this.slug = window.slugify(this.name);
                }
            },

            markSlugManual() {
                this.autoSlug = false;
                this.slug = window.slugify(this.slug);
            }
        }"
        action="{{ route('admin.cms.free-test-categories.store') }}"
        method="POST"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <x-admin.form.input
            label="Name"
            name="name"
            id="create_name"
            x-model="name"
            @input="syncSlug()"
            placeholder="Example: Grammar"
            :required="true" />

        <x-admin.form.input
            label="Slug"
            name="slug"
            id="create_slug"
            x-model="slug"
            @input="markSlugManual()"
            placeholder="Auto-generated from name" />

        <x-admin.form.textarea
            label="Description"
            name="description"
            id="create_description"
            :value="old('_form_type') === 'create' ? old('description') : ''"
            placeholder="Write a short category description..."
            rows="4" />

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
            submit-form="createFreeTestCategoryForm"
            submit-label="Save Category" />
    </x-slot:footer>
</x-admin.modal>