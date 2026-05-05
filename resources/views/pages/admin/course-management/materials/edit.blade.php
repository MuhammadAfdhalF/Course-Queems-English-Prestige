@extends('layouts.admin', [
'pageTitle' => 'Edit Module Material',
'pageSubtitle' => $moduleMaterial->module->title,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.modules.materials.index', $moduleMaterial->module)"
        back-label="Back to Materials" />

    <x-admin.flash-message />

    @include('partials.admin.course-management.materials.form', [
    'module' => $moduleMaterial->module,
    'material' => $moduleMaterial,
    'action' => route('admin.course-management.materials.update', $moduleMaterial),
    'method' => 'PUT',
    'submitLabel' => 'Save Changes',
    'nextSortOrder' => null,
    ])
</section>
@endsection

@push('scripts')
@vite(['resources/js/admin-rich-text.js'])
@endpush