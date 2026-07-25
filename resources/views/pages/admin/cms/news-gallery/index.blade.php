@extends('layouts.admin', [
'pageTitle' => 'News & Gallery',
'pageSubtitle' => 'Website CMS',
])

@section('content')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('newsGalleryAdmin', (config = {}) => ({
            createModalOpen: config.errorsAny && config.formType === 'create',
            editModalOpen: config.errorsAny && config.formType === 'edit',
            deleteModalOpen: false,
            imagePreviewModalOpen: false,
            imageSortOrder: 1,
            imageModalOpen: false,
            selectedPostImages: null,
            imagesList: [],
            draggedIndex: null,
            isOrderChanged: false,
            selectedPost: null,
            selectedItem: { title: '', delete_url: '#' },
            previewImage: { title: '', url: '' },

            openImageModal(post) {
                this.selectedPostImages = post;
                this.imagesList = JSON.parse(JSON.stringify(post.images || []));
                this.imageSortOrder = post.next_sort_order || 1;
                this.isOrderChanged = false;
                this.imageModalOpen = true;
            },

            moveImage(fromIndex, toIndex) {
                if (toIndex < 0 || toIndex >= this.imagesList.length) return;
                const item = this.imagesList.splice(fromIndex, 1)[0];
                this.imagesList.splice(toIndex, 0, item);
                this.isOrderChanged = true;
            },

            onDragStart(index, event) {
                this.draggedIndex = index;
                if (event && event.dataTransfer) {
                    event.dataTransfer.effectAllowed = 'move';
                }
            },

            onDrop(dropIndex) {
                if (this.draggedIndex === null || this.draggedIndex === dropIndex) return;
                this.moveImage(this.draggedIndex, dropIndex);
                this.draggedIndex = null;
            },

            saveOrder() {
                if (!this.selectedPostImages || !this.selectedPostImages.image_reorder_url) return;
                const orderIds = this.imagesList.map(img => img.id);

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = this.selectedPostImages.image_reorder_url;

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                }

                orderIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'order[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
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
        }));
    });
</script>

<section
    x-data="newsGalleryAdmin({
        errorsAny: {{ $errors->any() ? 'true' : 'false' }},
        formType: '{{ old('_form_type') }}'
    })"
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