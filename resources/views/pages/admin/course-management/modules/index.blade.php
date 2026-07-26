@extends('layouts.admin', [
'pageTitle' => 'Course Modules',
'pageSubtitle' => $courseLevel->name,
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit' ? 'true' : 'false' }},
        deleteModalOpen: false,

        selectedModule: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        openEditModal(item) {
            this.selectedModule = item;
            this.editModalOpen = true;
        },

        openDeleteModal(item) {
            this.selectedItem = item;
            this.deleteModalOpen = true;
        }
    }"
    @include('partials.admin.course-management.modules.header')

    @php
        $builderUrl = route('admin.course-management.programs.builder', [
            'courseProgram' => $courseLevel->course_program_id,
            'level' => $courseLevel->id
        ]);
    @endphp

    @include('partials.admin.course-management.legacy-builder-banner', ['builderUrl' => $builderUrl])

    <x-admin.flash-message />

    @include('partials.admin.course-management.modules.table')
    @include('partials.admin.course-management.modules.create-modal')
    @include('partials.admin.course-management.modules.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Module"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteModuleForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this module?" />
</section>
@endsection