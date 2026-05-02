@extends('layouts.admin', [
'pageTitle' => 'Profile Videos',
'pageSubtitle' => 'About Page CMS',
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,
        imagePreviewModalOpen: false,

        selectedProfileVideo: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        previewImage: {
            title: '',
            url: ''
        },

        openEditModal(item) {
            this.selectedProfileVideo = item;
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
    @include('partials.admin.cms.about.profile-videos.header')

    <x-admin.flash-message />

    @include('partials.admin.cms.about.profile-videos.table')
    @include('partials.admin.cms.about.profile-videos.create-modal')
    @include('partials.admin.cms.about.profile-videos.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Profile Video"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteProfileVideoForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this profile video?" />

    <x-admin.image-preview-modal
        model="imagePreviewModalOpen"
        title="Image Preview"
        subtitle="Preview profile video thumbnail." />
</section>
@endsection