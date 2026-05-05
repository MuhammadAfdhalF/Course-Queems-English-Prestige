@extends('layouts.admin', [
'pageTitle' => 'Add Module Material',
'pageSubtitle' => $module->title,
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.modules.materials.index', $module)"
        back-label="Back to Materials" />

    <x-admin.flash-message />

    @include('partials.admin.course-management.materials.form', [
    'module' => $module,
    'material' => null,
    'action' => route('admin.course-management.modules.materials.store', $module),
    'method' => 'POST',
    'submitLabel' => 'Save Material',
    'nextSortOrder' => $nextSortOrder,
    ])
</section>
@endsection

@push('scripts')
@vite(['resources/js/admin-rich-text.js'])
@endpush