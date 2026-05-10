@extends('layouts.admin', [
'pageTitle' => 'Free Test Results',
'pageSubtitle' => 'Website CMS',
])

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    @include('partials.admin.cms.free-test-results.header')

    <x-admin.flash-message />

    @include('partials.admin.cms.free-test-results.filters')
    @include('partials.admin.cms.free-test-results.table')

    @if ($results->hasPages())
    <div>
        {{ $results->links() }}
    </div>
    @endif
</section>
@endsection