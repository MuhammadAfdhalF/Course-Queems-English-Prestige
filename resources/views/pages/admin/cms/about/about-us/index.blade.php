@extends('layouts.admin', [
'pageTitle' => 'About Us',
'pageSubtitle' => 'About Page CMS',
])

@section('content')
<section
    x-data="{
        imagePreviewModalOpen: false,

        previewImage: {
            title: '',
            url: ''
        },

        openImagePreview(image) {
            this.previewImage = image;
            this.imagePreviewModalOpen = true;
        }
    }"
    class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.cms.about.index')"
        back-label="Back to About Page" />

    <x-admin.flash-message />

    @include('partials.admin.cms.about.about-us.form')

    <x-admin.image-preview-modal
        model="imagePreviewModalOpen"
        title="Image Preview"
        subtitle="Preview About Us image." />
</section>
@endsection