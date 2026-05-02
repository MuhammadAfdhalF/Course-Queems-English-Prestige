@extends('layouts.admin', [
'pageTitle' => 'Hero Sections',
'pageSubtitle' => 'Home Page CMS',
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,
        imagePreviewModalOpen: false,

        selectedHero: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        previewImage: {
            title: '',
            url: ''
        },

        openEditModal(hero) {
            this.selectedHero = hero;
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
    @include('partials.admin.cms.home.hero-sections.header')

    <x-admin.flash-message />

    @include('partials.admin.cms.home.hero-sections.table')
    @include('partials.admin.cms.home.hero-sections.create-modal')
    @include('partials.admin.cms.home.hero-sections.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Hero Section"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteHeroSectionForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this hero section?" />

    <x-admin.image-preview-modal
        model="imagePreviewModalOpen"
        title="Image Preview"
        subtitle="Preview hero section image." />
</section>
@endsection