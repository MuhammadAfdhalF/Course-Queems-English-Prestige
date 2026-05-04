@extends('layouts.admin', [
'pageTitle' => 'Course Programs',
'pageSubtitle' => 'Course Management',
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,

        selectedProgram: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        openEditModal(item) {
            this.selectedProgram = item;
            this.editModalOpen = true;
        },

        openDeleteModal(item) {
            this.selectedItem = item;
            this.deleteModalOpen = true;
        }
    }"
    class="mx-auto max-w-7xl space-y-6">
    @include('partials.admin.course-management.programs.header')

    <x-admin.flash-message />

    @include('partials.admin.course-management.programs.table')
    @include('partials.admin.course-management.programs.create-modal')
    @include('partials.admin.course-management.programs.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Course Program"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteCourseProgramForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this course program?" />
</section>
@endsection
