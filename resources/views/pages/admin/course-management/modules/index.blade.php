@extends('layouts.admin', [
'pageTitle' => 'Course Modules',
'pageSubtitle' => $courseLevel->name,
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,
        imagePreviewModalOpen: false,

        selectedModule: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        previewImage: {
            title: '',
            url: '',
        },

        openEditModal(item) {
            this.selectedModule = item;
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
    @include('partials.admin.course-management.modules.header')

    <x-admin.flash-message />

    @include('partials.admin.course-management.modules.table')
    @include('partials.admin.course-management.modules.create-modal')
    @include('partials.admin.course-management.modules.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Module"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteModuleForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this module?" />

    <x-admin.image-preview-modal
        model="imagePreviewModalOpen"
        title="Opening Media Preview"
        subtitle="Preview module opening image." />
</section>
@endsection