@extends('layouts.admin', [
'pageTitle' => 'Certificate Templates',
'pageSubtitle' => 'Certificate Management',
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,
        imagePreviewModalOpen: false,

        selectedTemplate: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        previewImage: {
            title: '',
            url: ''
        },

        openEditModal(template) {
            this.selectedTemplate = template;
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
    @include('partials.admin.course-management.certificate-templates.header')

    <x-admin.flash-message />

    @include('partials.admin.course-management.certificate-templates.table')
    @include('partials.admin.course-management.certificate-templates.create-modal')
    @include('partials.admin.course-management.certificate-templates.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Certificate Template"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteCertificateTemplateForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this certificate template?" />

    <x-admin.image-preview-modal
        model="imagePreviewModalOpen"
        title="Template Preview"
        subtitle="Preview certificate template background image." />
</section>
@endsection