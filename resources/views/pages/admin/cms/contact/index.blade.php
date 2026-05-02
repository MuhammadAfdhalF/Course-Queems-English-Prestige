@extends('layouts.admin', [
'pageTitle' => 'Contact Page',
'pageSubtitle' => 'Website CMS',
])

@section('content')
<section
    x-data="{
        createModalOpen: {{ $errors->any() && old('_form_type') === 'create_social_link' ? 'true' : 'false' }},
        editModalOpen: {{ $errors->any() && old('_form_type') === 'edit_social_link' ? 'true' : 'false' }},
        deleteModalOpen: false,

        selectedSocialLink: null,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        openEditModal(item) {
            this.selectedSocialLink = item;
            this.editModalOpen = true;
        },

        openDeleteModal(item) {
            this.selectedItem = item;
            this.deleteModalOpen = true;
        }
    }"
    class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.cms.home.index')"
        back-label="Back to CMS Home">
        <x-slot:actions>
            <button
                type="button"
                @click="createModalOpen = true"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                <x-admin.icon name="plus" class="h-5 w-5" />
                <span>Add Social Link</span>
            </button>
        </x-slot:actions>
    </x-admin.page-toolbar>

    <x-admin.flash-message />

    @include('partials.admin.cms.contact.form')
    @include('partials.admin.cms.contact.social-links-table')
    @include('partials.admin.cms.contact.create-social-link-modal')
    @include('partials.admin.cms.contact.edit-social-link-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Social Link"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteSocialLinkForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this social link?" />
</section>
@endsection