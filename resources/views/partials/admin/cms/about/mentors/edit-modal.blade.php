<x-admin.modal
    model="editModalOpen"
    title="Edit Mentor"
    subtitle="Update the selected mentor profile."
    size="lg">
    <template x-if="selectedMentor">
        <form
            id="editMentorForm"
            :action="selectedMentor.update_url"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <x-admin.form.input
                label="Name"
                name="name"
                id="edit_name"
                x-model="selectedMentor.name"
                :required="true" />

            <div x-show="selectedMentor.photo_url">
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Current Photo
                </label>

                <button
                    type="button"
                    @click="openImagePreview({
                        title: selectedMentor.name,
                        url: selectedMentor.photo_url
                    })"
                    class="group relative overflow-hidden rounded-xl">
                    <img
                        :src="selectedMentor.photo_url"
                        :alt="selectedMentor.name"
                        class="h-28 w-28 rounded-xl object-cover transition duration-200 group-hover:scale-105">

                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                        <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                    </span>
                </button>
            </div>

            <x-admin.form.file
                label="Replace Photo"
                name="photo"
                id="edit_photo"
                accept="image/*"
                hint="Leave empty if you do not want to replace the current photo." />

            <x-admin.form.input
                label="Position"
                name="position"
                id="edit_position"
                x-model="selectedMentor.position" />

            <x-admin.form.input
                label="Expertise"
                name="expertise"
                id="edit_expertise"
                x-model="selectedMentor.expertise" />

            <x-admin.form.textarea
                label="Description"
                name="description"
                id="edit_description"
                x-model="selectedMentor.description"
                rows="5" />

            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="edit_sort_order"
                type="number"
                min="0"
                x-model="selectedMentor.sort_order" />

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="edit_is_active"
                x-model="selectedMentor.is_active" />
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editMentorForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>