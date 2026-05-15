<x-admin.modal
    model="editModalOpen"
    title="Edit Certificate Template"
    subtitle="Update the selected certificate template."
    size="lg">
    <template x-if="selectedTemplate">
        <form
            id="editCertificateTemplateForm"
            :action="selectedTemplate.update_url"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <x-admin.form.input
                label="Template Name"
                name="name"
                id="edit_name"
                x-model="selectedTemplate.name"
                :required="true" />

            <x-admin.form.select
                label="Course Program"
                name="course_program_id"
                id="edit_course_program_id"
                x-model="selectedTemplate.course_program_id"
                :options="$coursePrograms->pluck('name', 'id')->prepend('Global Template', '')->toArray()"
                hint="Choose Global Template if this template can be used for all programs." />

            <div x-show="selectedTemplate.background_image_url">
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Current Background
                </label>

                <button
                    type="button"
                    @click="openImagePreview({
                        title: selectedTemplate.name,
                        url: selectedTemplate.background_image_url
                    })"
                    class="group relative block overflow-hidden rounded-xl border border-slate-200">
                    <img
                        :src="selectedTemplate.background_image_url"
                        :alt="selectedTemplate.name"
                        class="h-32 w-full rounded-xl object-cover transition duration-200 group-hover:scale-105">

                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                        <x-admin.icon name="eye" class="h-6 w-6 opacity-0 transition group-hover:opacity-100" />
                    </span>
                </button>
            </div>

            <x-admin.form.file
                label="Replace Background Image"
                name="background_image"
                id="edit_background_image"
                accept="image/*"
                hint="Leave empty if you do not want to replace the current background." />

            <div class="grid gap-6 md:grid-cols-2">
                <x-admin.form.checkbox
                    label="Default Template"
                    name="is_default"
                    id="edit_is_default"
                    x-model="selectedTemplate.is_default" />

                <x-admin.form.checkbox
                    label="Active"
                    name="is_active"
                    id="edit_is_active"
                    x-model="selectedTemplate.is_active" />
            </div>
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editCertificateTemplateForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>