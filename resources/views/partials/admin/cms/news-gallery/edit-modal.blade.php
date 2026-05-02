<x-admin.modal
    model="editModalOpen"
    title="Edit Post"
    subtitle="Update the selected news or gallery post."
    size="xl">
    <template x-if="selectedPost">
        <form
            id="editInformationPostForm"
            x-data="{
                get linkType() {
                    return selectedPost.link_type;
                },
                set linkType(value) {
                    selectedPost.link_type = value;
                }
            }"
            :action="selectedPost.update_url"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <div class="grid gap-6 md:grid-cols-2">
                <x-admin.form.input
                    label="Title"
                    name="title"
                    id="edit_title"
                    x-model="selectedPost.title"
                    :required="true" />

                <x-admin.form.input
                    label="Slug"
                    name="slug"
                    id="edit_slug"
                    x-model="selectedPost.slug" />

                <x-admin.form.select
                    label="Type"
                    name="type"
                    id="edit_type"
                    x-model="selectedPost.type"
                    :options="[
                        'news' => 'News',
                        'gallery' => 'Gallery',
                        'event' => 'Event',
                        'announcement' => 'Announcement',
                    ]"
                    :required="true" />

                <x-admin.form.select
                    label="Link Type"
                    name="link_type"
                    id="edit_link_type"
                    x-model="selectedPost.link_type"
                    :options="[
                        'internal' => 'Internal Detail Page',
                        'external' => 'External URL',
                    ]"
                    :required="true" />

                <div x-show="selectedPost.link_type === 'external'" class="md:col-span-2">
                    <x-admin.form.input
                        label="External URL"
                        name="external_url"
                        id="edit_external_url"
                        type="url"
                        x-model="selectedPost.external_url" />
                </div>

                <x-admin.form.input
                    label="Event Date"
                    name="event_date"
                    id="edit_event_date"
                    type="date"
                    x-model="selectedPost.event_date" />

                <x-admin.form.input
                    label="Published At"
                    name="published_at"
                    id="edit_published_at"
                    type="datetime-local"
                    x-model="selectedPost.published_at" />
            </div>

            <div x-show="selectedPost.thumbnail_url">
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Current Thumbnail
                </label>

                <button
                    type="button"
                    @click="openImagePreview({
                        title: selectedPost.title,
                        url: selectedPost.thumbnail_url
                    })"
                    class="group relative overflow-hidden rounded-xl">
                    <img
                        :src="selectedPost.thumbnail_url"
                        :alt="selectedPost.title"
                        class="h-32 w-56 rounded-xl object-cover transition duration-200 group-hover:scale-105">

                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                        <x-admin.icon name="eye" class="h-6 w-6 opacity-0 transition group-hover:opacity-100" />
                    </span>
                </button>
            </div>

            <x-admin.form.file
                label="Replace Thumbnail"
                name="thumbnail"
                id="edit_thumbnail"
                accept="image/*"
                hint="Leave empty if you do not want to replace the current thumbnail." />

            <x-admin.form.textarea
                label="Excerpt"
                name="excerpt"
                id="edit_excerpt"
                x-model="selectedPost.excerpt"
                rows="3" />

            <div x-show="selectedPost.link_type === 'internal'">
                <x-admin.form.textarea
                    label="Content"
                    name="content"
                    id="edit_content"
                    x-model="selectedPost.content"
                    rows="8" />
            </div>

            <x-admin.form.checkbox
                label="Published"
                name="is_published"
                id="edit_is_published"
                x-model="selectedPost.is_published" />
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editInformationPostForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>