@extends('layouts.admin', [
'pageTitle' => 'FAQ',
'pageSubtitle' => 'Home Page CMS',
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,

        selectedFaq: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        openEditModal(faq) {
            this.selectedFaq = faq;
            this.editModalOpen = true;
        },

        openDeleteModal(item) {
            this.selectedItem = item;
            this.deleteModalOpen = true;
        }
    }"
    class="mx-auto max-w-7xl space-y-6">
    @include('partials.admin.cms.home.faqs.header')

    <x-admin.flash-message />

    @include('partials.admin.cms.home.faqs.table')
    @include('partials.admin.cms.home.faqs.create-modal')
    @include('partials.admin.cms.home.faqs.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete FAQ"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteFaqForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this FAQ?" />
</section>
@endsection