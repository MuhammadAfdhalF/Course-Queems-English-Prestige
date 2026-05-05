<x-admin.modal
    model="editModalOpen"
    title="Edit Module"
    subtitle="Update the selected learning module."
    size="xl">
    <template x-if="selectedModule">
        <form
            id="editModuleForm"
            :action="selectedModule.update_url"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <div class="grid gap-6 md:grid-cols-2">
                <x-admin.form.input
                    label="Module Title"
                    name="title"
                    id="edit_title"
                    x-model="selectedModule.title"
                    :required="true" />

                <x-admin.form.input
                    label="Slug"
                    name="slug"
                    id="edit_slug"
                    x-model="selectedModule.slug" />
            </div>

            <x-admin.form.textarea
                label="Short Description"
                name="short_description"
                id="edit_short_description"
                x-model="selectedModule.short_description"
                rows="3" />

            <div class="grid gap-6 md:grid-cols-2">
                <x-admin.form.select
                    label="Opening Media Type"
                    name="opening_media_type"
                    id="edit_opening_media_type"
                    x-model="selectedModule.opening_media_type"
                    :options="[
                        'image' => 'Image',
                        'video' => 'Video',
                    ]"
                    :required="true" />

                <x-admin.form.file
                    label="Replace Opening Media"
                    name="opening_media_file"
                    id="edit_opening_media_file"
                    accept="image/*,video/*"
                    hint="Leave empty if you do not want to replace the current media." />
            </div>

            <div x-show="selectedModule.opening_media_url">
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Current Opening Media
                </label>

                <template x-if="selectedModule.opening_media_type === 'image'">
                    <button
                        type="button"
                        @click="openImagePreview({
                            title: selectedModule.title,
                            url: selectedModule.opening_media_url
                        })"
                        class="group relative overflow-hidden rounded-xl">
                        <img
                            :src="selectedModule.opening_media_url"
                            :alt="selectedModule.title"
                            class="h-32 w-56 rounded-xl object-cover transition duration-200 group-hover:scale-105">

                        <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                            <x-admin.icon name="eye" class="h-6 w-6 opacity-0 transition group-hover:opacity-100" />
                        </span>
                    </button>
                </template>

                <template x-if="selectedModule.opening_media_type === 'video'">
                    <video
                        :src="selectedModule.opening_media_url"
                        controls
                        class="h-32 w-56 rounded-xl bg-slate-900 object-cover">
                    </video>
                </template>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <x-admin.form.input
                    label="Sort Order"
                    name="sort_order"
                    id="edit_sort_order"
                    type="number"
                    min="0"
                    x-model="selectedModule.sort_order" />

                <div class="flex items-end">
                    <x-admin.form.checkbox
                        label="Preview Module"
                        name="is_preview"
                        id="edit_is_preview"
                        x-model="selectedModule.is_preview" />
                </div>

                <div class="flex items-end">
                    <x-admin.form.checkbox
                        label="Active"
                        name="is_active"
                        id="edit_is_active"
                        x-model="selectedModule.is_active" />
                </div>
            </div>
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editModuleForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>