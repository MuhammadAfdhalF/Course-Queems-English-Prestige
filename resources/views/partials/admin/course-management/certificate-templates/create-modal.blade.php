<x-admin.modal
    model="createModalOpen"
    title="Add Certificate Template"
    subtitle="Create a reusable certificate template."
    size="lg">
    <form
        id="createCertificateTemplateForm"
        action="{{ route('admin.course-management.certificate-templates.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <x-admin.form.input
            label="Template Name"
            name="name"
            id="create_name"
            :value="old('_form_type') === 'create' ? old('name') : ''"
            placeholder="Example: Queens Prestige Default"
            :required="true" />

        <x-admin.form.select
            label="Course Program"
            name="course_program_id"
            id="create_course_program_id"
            :value="old('_form_type') === 'create' ? old('course_program_id') : ''"
            :options="$coursePrograms->pluck('name', 'id')->prepend('Global Template', '')->toArray()"
            hint="Choose Global Template if this template can be used for all programs." />

        <x-admin.form.file
            label="Background Image"
            name="background_image"
            id="create_background_image"
            accept="image/*"
            hint="Upload a certificate background image. Max 4MB." />

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.checkbox
                label="Default Template"
                name="is_default"
                id="create_is_default"
                :checked="old('_form_type') === 'create' ? (bool) old('is_default', false) : false" />

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="create_is_active"
                :checked="old('_form_type') === 'create' ? (bool) old('is_active', true) : true" />
        </div>
    </form>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="createModalOpen = false"
            submit-form="createCertificateTemplateForm"
            submit-label="Save Template" />
    </x-slot:footer>
</x-admin.modal>