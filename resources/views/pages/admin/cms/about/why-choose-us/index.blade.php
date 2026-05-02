@extends('layouts.admin', [
    'pageTitle' => 'Why Choose Us',
    'pageSubtitle' => 'About Page CMS',
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,
        imagePreviewModalOpen: false,

        selectedWhyChooseUs: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        previewImage: {
            title: '',
            url: ''
        },

        openEditModal(item) {
            this.selectedWhyChooseUs = item;
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
    class="mx-auto max-w-7xl space-y-6"
>
    @include('partials.admin.cms.about.why-choose-us.header')

    <x-admin.flash-message />

    @include('partials.admin.cms.about.why-choose-us.table')
    @include('partials.admin.cms.about.why-choose-us.create-modal')
    @include('partials.admin.cms.about.why-choose-us.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Why Choose Us Item"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteWhyChooseUsForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this item?" />

    <x-admin.image-preview-modal
        model="imagePreviewModalOpen"
        title="Image Preview"
        subtitle="Preview Why Choose Us icon." />
</section>
@endsection