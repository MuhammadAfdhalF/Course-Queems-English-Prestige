@extends('layouts.admin', [
'pageTitle' => 'Free Test Questions',
'pageSubtitle' => $freeTest->title,
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,

        selectedQuestion: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        openEditModal(item) {
            this.selectedQuestion = item;
            this.editModalOpen = true;
        },

        openDeleteModal(item) {
            this.selectedItem = item;
            this.deleteModalOpen = true;
        }
    }"
    class="mx-auto max-w-7xl space-y-6">
    @include('partials.admin.cms.free-tests.questions.header')

    <x-admin.flash-message />

    @include('partials.admin.cms.free-tests.questions.table')
    @include('partials.admin.cms.free-tests.questions.create-modal')
    @include('partials.admin.cms.free-tests.questions.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Question"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteFreeTestQuestionForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this question?" />
</section>
@endsection