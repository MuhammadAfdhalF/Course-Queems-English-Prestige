@extends('layouts.admin', [
'pageTitle' => 'Free Tests',
'pageSubtitle' => 'Website CMS',
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,

        selectedFreeTest: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        openEditModal(item) {
            this.selectedFreeTest = item;
            this.editModalOpen = true;
        },

        openDeleteModal(item) {
            this.selectedItem = item;
            this.deleteModalOpen = true;
        }
    }"
    class="mx-auto max-w-7xl space-y-6">
    @include('partials.admin.cms.free-tests.header')

    <x-admin.flash-message />

    @include('partials.admin.cms.free-tests.table')
    @include('partials.admin.cms.free-tests.create-modal')
    @include('partials.admin.cms.free-tests.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Free Test"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteFreeTestForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this free test?" />
</section>
@endsection