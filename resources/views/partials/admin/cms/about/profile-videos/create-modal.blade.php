<x-admin.modal
    model="createModalOpen"
    title="Add Profile Video"
    subtitle="Upload a profile video for the About page."
    size="lg">
    <form
        id="createProfileVideoForm"
        action="{{ route('admin.cms.profile-videos.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <x-admin.form.input
            label="Title"
            name="title"
            id="create_title"
            :value="old('_form_type') === 'create' ? old('title') : ''"
            placeholder="Example: Queens English Profile Video"
            :required="true" />

        <x-admin.form.textarea
            label="Description"
            name="description"
            id="create_description"
            :value="old('_form_type') === 'create' ? old('description') : ''"
            placeholder="Write a short description here..."
            rows="5" />

        <x-admin.form.file
            label="Video File"
            name="video_file"
            id="create_video_file"
            accept="video/mp4,video/quicktime,video/x-msvideo,video/webm"
            hint="Allowed: mp4, mov, avi, webm. Max 50MB." />

        <x-admin.form.file
            label="Thumbnail"
            name="thumbnail"
            id="create_thumbnail"
            accept="image/*"
            hint="Recommended size: 1280x720px. Max 2MB." />

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
            submit-form="createProfileVideoForm"
            submit-label="Save Profile Video" />
    </x-slot:footer>
</x-admin.modal>