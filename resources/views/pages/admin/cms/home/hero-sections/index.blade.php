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
        previewImage: {
            title: '',
            url: '',
        },

        openEditModal(hero) {
            this.selectedHero = hero;
            this.editModalOpen = true;
        },

        openDeleteModal(hero) {
            this.selectedHero = hero;
            this.deleteModalOpen = true;
        },

        openImagePreview(image) {
            this.previewImage = image;
            this.imagePreviewModalOpen = true;
        }
    }"
    class="mx-auto max-w-7xl space-y-6">

    @include('partials.admin.cms.home.hero-sections.header')
    @include('partials.admin.cms.home.hero-sections.alerts')
    @include('partials.admin.cms.home.hero-sections.table')
    @include('partials.admin.cms.home.hero-sections.create-modal')
    @include('partials.admin.cms.home.hero-sections.edit-modal')
    @include('partials.admin.cms.home.hero-sections.delete-modal')
    <x-admin.image-preview-modal
        model="imagePreviewModalOpen"
        title="Image Preview"
        subtitle="Preview gambar hero section." />
</section>
@endsection