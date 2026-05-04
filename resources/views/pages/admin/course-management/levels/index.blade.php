@extends('layouts.admin', [
'pageTitle' => 'Course Levels',
'pageSubtitle' => $courseProgram->name,
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
    class="mx-auto max-w-7xl space-y-6">
    @include('partials.admin.course-management.levels.header')

    <x-admin.flash-message />

    @include('partials.admin.course-management.levels.table')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Course Level"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteCourseLevelForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this course level?" />

    <x-admin.image-preview-modal
        model="imagePreviewModalOpen"
        title="Thumbnail Preview"
        subtitle="Preview course level thumbnail." />
</section>
@endsection