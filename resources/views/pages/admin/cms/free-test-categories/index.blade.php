@extends('layouts.admin', [
'pageTitle' => 'Free Test Categories',
'pageSubtitle' => 'Website CMS',
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,

        selectedCategory: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        openEditModal(item) {
            this.selectedCategory = item;
            this.editModalOpen = true;
        },

        openDeleteModal(item) {
            this.selectedItem = item;
            this.deleteModalOpen = true;
        }
    }"
    class="mx-auto max-w-7xl space-y-6">
    @include('partials.admin.cms.free-test-categories.header')

    <x-admin.flash-message />

    @include('partials.admin.cms.free-test-categories.table')
    @include('partials.admin.cms.free-test-categories.create-modal')
    @include('partials.admin.cms.free-test-categories.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Free Test Category"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteFreeTestCategoryForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this category?" />
</section>
@endsection