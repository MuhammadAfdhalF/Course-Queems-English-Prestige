<x-admin.modal
    model="createModalOpen"
    title="Add Module"
    subtitle="Create a learning module for this course level."
    size="xl">
    <form
        id="createModuleForm"
        x-data="{
            title: @js(old('_form_type') === 'create' ? old('title', '') : ''),
            slug: @js(old('_form_type') === 'create' ? old('slug', '') : ''),
            autoSlug: {{ old('_form_type') === 'create' && old('slug') ? 'false' : 'true' }},
            openingMediaType: @js(old('_form_type') === 'create' ? old('opening_media_type', 'image') : 'image'),

            syncSlug() {
                if (this.autoSlug) {
                    this.slug = window.slugify(this.title);
                }
            },

            markSlugManual() {
                this.autoSlug = false;
                this.slug = window.slugify(this.slug);
            }
        }"
        action="{{ route('admin.course-management.levels.modules.store', $courseLevel) }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.input
                label="Module Title"
                name="title"
                id="create_title"
                x-model="title"
                @input="syncSlug()"
                placeholder="Example: Module 01 - Grammar Foundation"
                :required="true" />

            <x-admin.form.input
                label="Slug"
                name="slug"
                id="create_slug"
                x-model="slug"
                @input="markSlugManual()"
                placeholder="Auto-generated from module title" />
        </div>

        <x-admin.form.textarea
            label="Short Description"
            name="short_description"
            id="create_short_description"
            :value="old('_form_type') === 'create' ? old('short_description') : ''"
            placeholder="Write a short summary for this module..."
            rows="3" />

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.select
                label="Opening Media Type"
                name="opening_media_type"
                id="create_opening_media_type"
                x-model="openingMediaType"
                :options="[
                    'image' => 'Image',
                    'video' => 'Video',
                ]"
                :required="true" />

            <x-admin.form.file
                label="Opening Media File"
                name="opening_media_file"
                id="create_opening_media_file"
                accept="image/*,video/*"
                hint="Image max 4MB. Video max 20MB." />
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="create_sort_order"
                type="number"
                min="0"
                :value="old('_form_type') === 'create' ? old('sort_order', $nextSortOrder) : $nextSortOrder" />

            <div class="flex items-end">
                <x-admin.form.checkbox
                    label="Preview Module"
                    name="is_preview"
                    id="create_is_preview"
                    :checked="old('_form_type') === 'create' ? (bool) old('is_preview', false) : false" />
            </div>

            <div class="flex items-end">
                <x-admin.form.checkbox
                    label="Active"
                    name="is_active"
                    id="create_is_active"
                    :checked="old('_form_type') === 'create' ? (bool) old('is_active', true) : true" />
            </div>
        </div>
    </form>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="createModalOpen = false"
            submit-form="createModuleForm"
            submit-label="Save Module" />
    </x-slot:footer>
</x-admin.modal>