<x-admin.modal
    model="editModalOpen"
    title="Edit Profile Video"
    subtitle="Update the selected profile video."
    size="lg">
    <template x-if="selectedProfileVideo">
        <form
            id="editProfileVideoForm"
            :action="selectedProfileVideo.update_url"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <x-admin.form.input
                label="Title"
                name="title"
                id="edit_title"
                x-model="selectedProfileVideo.title"
                :required="true" />

            <x-admin.form.textarea
                label="Description"
                name="description"
                id="edit_description"
                x-model="selectedProfileVideo.description"
                rows="5" />

            <div x-show="selectedProfileVideo.thumbnail_url">
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Current Thumbnail
                </label>

                <button
                    type="button"
                    @click="openImagePreview({
                        title: selectedProfileVideo.title,
                        url: selectedProfileVideo.thumbnail_url
                    })"
                    class="group relative overflow-hidden rounded-xl">
                    <img
                        :src="selectedProfileVideo.thumbnail_url"
                        :alt="selectedProfileVideo.title"
                        class="h-28 w-48 rounded-xl object-cover transition duration-200 group-hover:scale-105">

                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                        <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                    </span>
                </button>
            </div>

            <template x-if="selectedProfileVideo.video_url">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Current Video
                    </label>

                    <a
                        :href="selectedProfileVideo.video_url"
                        target="_blank"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-50 px-4 py-3 text-sm font-bold text-blue-700 transition hover:bg-blue-100">
                        View Current Video
                    </a>
                </div>
            </template>

            <x-admin.form.file
                label="Replace Video File"
                name="video_file"
                id="edit_video_file"
                accept="video/mp4,video/quicktime,video/x-msvideo,video/webm"
                hint="Leave empty if you do not want to replace the current video." />

            <x-admin.form.file
                label="Replace Thumbnail"
                name="thumbnail"
                id="edit_thumbnail"
                accept="image/*"
                hint="Leave empty if you do not want to replace the current thumbnail." />

            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="edit_sort_order"
                type="number"
                min="0"
                x-model="selectedProfileVideo.sort_order" />

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="edit_is_active"
                x-model="selectedProfileVideo.is_active" />
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editProfileVideoForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>