<x-admin.modal
    model="createModalOpen"
    title="Add Course Program"
    subtitle="Create a main course program."
    size="lg">
    <form
        id="createCourseProgramForm"
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
        action="{{ route('admin.course-management.programs.store') }}"
        method="POST"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <x-admin.form.input
            label="Program Name"
            name="name"
            id="create_name"
            x-model="name"
            @input="syncSlug()"
            placeholder="Example: General English"
            :required="true" />

        <x-admin.form.input
            label="Slug"
            name="slug"
            id="create_slug"
            x-model="slug"
            @input="markSlugManual()"
            placeholder="Auto-generated from program name" />

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
            submit-form="createCourseProgramForm"
            submit-label="Save Program" />
    </x-slot:footer>


</x-admin.modal>