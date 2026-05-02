<x-admin.modal
    model="createModalOpen"
    title="Add Post"
    subtitle="Create a news, gallery, event, or announcement post."
    size="xl">
    <form
        id="createInformationPostForm"
        x-data="{
        linkType: '{{ old('_form_type') === 'create' ? old('link_type', 'internal') : 'internal' }}',
        title: @js(old('_form_type') === 'create' ? old('title', '') : ''),
        slug: @js(old('_form_type') === 'create' ? old('slug', '') : ''),
        autoSlug: {{ old('_form_type') === 'create' && old('slug') ? 'false' : 'true' }},

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
        action="{{ route('admin.cms.news-gallery.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.input
                label="Title"
                name="title"
                id="create_title"
                x-model="title"
                @input="syncSlug()"
                placeholder="Example: Queens English Graduation Moment"
                :required="true" />
                
            <x-admin.form.input
                label="Slug"
                name="slug"
                id="create_slug"
                x-model="slug"
                @input="markSlugManual()"
                placeholder="Auto-generated from title" />

            <x-admin.form.select
                label="Type"
                name="type"
                id="create_type"
                :value="old('_form_type') === 'create' ? old('type') : 'news'"
                :options="[
                    'news' => 'News',
                    'gallery' => 'Gallery',
                    'event' => 'Event',
                    'announcement' => 'Announcement',
                ]"
                placeholder="Select type"
                :required="true" />

            <x-admin.form.select
                label="Link Type"
                name="link_type"
                id="create_link_type"
                x-model="linkType"
                :options="[
                    'internal' => 'Internal Detail Page',
                    'external' => 'External URL',
                ]"
                :required="true" />

            <div x-show="linkType === 'external'" class="md:col-span-2">
                <x-admin.form.input
                    label="External URL"
                    name="external_url"
                    id="create_external_url"
                    type="url"
                    :value="old('_form_type') === 'create' ? old('external_url') : ''"
                    placeholder="Example: https://instagram.com/p/xxxxx" />
            </div>

            <x-admin.form.input
                label="Event Date"
                name="event_date"
                id="create_event_date"
                type="date"
                :value="old('_form_type') === 'create' ? old('event_date') : ''" />

            <x-admin.form.input
                label="Published At"
                name="published_at"
                id="create_published_at"
                type="datetime-local"
                :value="old('_form_type') === 'create' ? old('published_at') : ''" />
        </div>

        <x-admin.form.file
            label="Thumbnail"
            name="thumbnail"
            id="create_thumbnail"
            accept="image/*"
            hint="Recommended size: 1200x800px. Max 2MB." />

        <x-admin.form.textarea
            label="Excerpt"
            name="excerpt"
            id="create_excerpt"
            :value="old('_form_type') === 'create' ? old('excerpt') : ''"
            placeholder="Write a short summary here..."
            rows="3" />

        <div x-show="linkType === 'internal'">
            <x-admin.form.textarea
                label="Content"
                name="content"
                id="create_content"
                :value="old('_form_type') === 'create' ? old('content') : ''"
                placeholder="Write the full content here..."
                rows="8" />
        </div>

        <x-admin.form.checkbox
            label="Published"
            name="is_published"
            id="create_is_published"
            :checked="old('_form_type') === 'create' ? (bool) old('is_published', true) : true" />
    </form>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="createModalOpen = false"
            submit-form="createInformationPostForm"
            submit-label="Save Post" />
    </x-slot:footer>
</x-admin.modal>