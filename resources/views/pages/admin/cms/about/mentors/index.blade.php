@extends('layouts.admin', [
'pageTitle' => 'Mentors',
'pageSubtitle' => 'About Page CMS',
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,
        imagePreviewModalOpen: false,

        selectedMentor: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        previewImage: {
            title: '',
            url: ''
        },

        openEditModal(item) {
            this.selectedMentor = item;
            this.editModalOpen = true;
        },

        openDeleteModal(item) {
            this.selectedItem = item;
            this.deleteModalOpen = true;
        },

        openImagePreview(image) {
            this.previewImage = image;
            this.imagePreviewModalOpen = true;
        }
    }"
    class="mx-auto max-w-7xl space-y-6">
    @include('partials.admin.cms.about.mentors.header')

    <x-admin.flash-message />

    @include('partials.admin.cms.about.mentors.table')
    @include('partials.admin.cms.about.mentors.create-modal')
    @include('partials.admin.cms.about.mentors.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Mentor"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteMentorForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this mentor?" />

    <x-admin.image-preview-modal
        model="imagePreviewModalOpen"
        title="Image Preview"
        subtitle="Preview mentor photo." />
</section>
@endsection