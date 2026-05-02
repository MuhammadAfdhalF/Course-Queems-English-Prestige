@extends('layouts.admin', [
'pageTitle' => 'News & Gallery',
'pageSubtitle' => 'Website CMS',
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,
        imagePreviewModalOpen: false,
        imageSortOrder: 1,
        imageModalOpen: false,
        selectedPostImages: null,
        openImageModal(post) {
            this.selectedPostImages = post;
            this.imageSortOrder = post.next_sort_order || 1;
            this.imageModalOpen = true;
        },
        selectedPost: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        previewImage: {
            title: '',
            url: ''
        },

        openEditModal(item) {
            this.selectedPost = item;
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
    @include('partials.admin.cms.news-gallery.header')

    <x-admin.flash-message />

    @include('partials.admin.cms.news-gallery.table')
    @include('partials.admin.cms.news-gallery.create-modal')
    @include('partials.admin.cms.news-gallery.edit-modal')
    @include('partials.admin.cms.news-gallery.images-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Post"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteInformationPostForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this post?" />

    <x-admin.image-preview-modal
        model="imagePreviewModalOpen"
        title="Image Preview"
        subtitle="Preview post thumbnail." />
</section>
@endsection