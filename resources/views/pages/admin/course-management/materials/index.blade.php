@extends('layouts.admin', [
'pageTitle' => 'Module Materials',
'pageSubtitle' => $module->title,
])

@section('content')
<section
    x-data="{
        deleteModalOpen: false,
        imagePreviewModalOpen: false,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        previewImage: {
            title: '',
            url: '',
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
    @include('partials.admin.course-management.materials.header')

    @php
        $builderUrl = route('admin.course-management.programs.builder', [
            'courseProgram' => $module->courseLevel->course_program_id,
            'level' => $module->course_level_id,
            'module' => $module->id,
            'tab' => 'materials'
        ]);
    @endphp

    @include('partials.admin.course-management.legacy-builder-banner', ['builderUrl' => $builderUrl])

    <x-admin.flash-message />

    @include('partials.admin.course-management.materials.table')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Material"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteModuleMaterialForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this material?" />

    <x-admin.image-preview-modal
        model="imagePreviewModalOpen"
        title="Material Preview"
        subtitle="Preview material image." />
</section>
@endsection