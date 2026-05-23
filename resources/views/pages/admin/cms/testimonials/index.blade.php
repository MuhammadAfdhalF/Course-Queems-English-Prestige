@extends('layouts.admin', [
'pageTitle' => 'Testimonials',
'pageSubtitle' => 'Homepage Display',
])

@section('content')
@php
$activeFilterClass = 'bg-[var(--color-brand-blue)] text-white border-[var(--color-brand-blue)]';
$inactiveFilterClass = 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50';
@endphp

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

    <x-admin.table-card class="p-5">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                    Testimonial Type
                </p>

                <div class="mt-3 flex flex-wrap gap-2">
                    <a
                        href="{{ route('admin.cms.testimonials.index', ['type' => 'all', 'visibility' => $visibility]) }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border px-4 text-sm font-black transition {{ $type === 'all' ? $activeFilterClass : $inactiveFilterClass }}">
                        All Types
                    </a>

                    <a
                        href="{{ route('admin.cms.testimonials.index', ['type' => 'course', 'visibility' => $visibility]) }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border px-4 text-sm font-black transition {{ $type === 'course' ? $activeFilterClass : $inactiveFilterClass }}">
                        Course
                    </a>

                    <a
                        href="{{ route('admin.cms.testimonials.index', ['type' => 'company', 'visibility' => $visibility]) }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border px-4 text-sm font-black transition {{ $type === 'company' ? $activeFilterClass : $inactiveFilterClass }}">
                        Company
                    </a>
                </div>
            </div>

            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400 xl:text-right">
                    Homepage Visibility
                </p>

                <div class="mt-3 flex flex-wrap gap-2 xl:justify-end">
                    <a
                        href="{{ route('admin.cms.testimonials.index', ['type' => $type, 'visibility' => 'all']) }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border px-4 text-sm font-black transition {{ $visibility === 'all' ? $activeFilterClass : $inactiveFilterClass }}">
                        All
                    </a>

                    <a
                        href="{{ route('admin.cms.testimonials.index', ['type' => $type, 'visibility' => 'visible']) }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border px-4 text-sm font-black transition {{ $visibility === 'visible' ? $activeFilterClass : $inactiveFilterClass }}">
                        On Home
                    </a>

                    <a
                        href="{{ route('admin.cms.testimonials.index', ['type' => $type, 'visibility' => 'hidden']) }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border px-4 text-sm font-black transition {{ $visibility === 'hidden' ? $activeFilterClass : $inactiveFilterClass }}">
                        Hidden
                    </a>

                    @if ($type !== 'all' || $visibility !== 'all')
                    <a
                        href="{{ route('admin.cms.testimonials.index') }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-black text-slate-600 transition hover:bg-slate-100">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </x-admin.table-card>

    @include('partials.admin.cms.testimonials.table')

    @if ($testimonials->hasPages())
    <div>
        {{ $testimonials->links() }}
    </div>
    @endif

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