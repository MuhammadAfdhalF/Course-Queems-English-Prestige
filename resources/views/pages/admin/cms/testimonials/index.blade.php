@extends('layouts.admin', [
'pageTitle' => 'Testimonials',
'pageSubtitle' => 'CMS Moderation',
])

@section('content')
<section
    x-data="{
        deleteModalOpen: false,

        selectedItem: {
            title: '',
            delete_url: '#'
        },

        openDeleteModal(item) {
            this.selectedItem = item;
            this.deleteModalOpen = true;
        }
    }"
    class="mx-auto max-w-7xl space-y-6">
    @include('partials.admin.cms.testimonials.header')

    <x-admin.flash-message />

    @include('partials.admin.cms.testimonials.table')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Testimonial"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteTestimonialForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this testimonial?" />
</section>
@endsection