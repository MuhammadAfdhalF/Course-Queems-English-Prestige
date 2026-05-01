@extends('layouts.admin', [
'pageTitle' => 'Hero Sections',
'pageSubtitle' => 'Home Page CMS',
])

@section('content')
<section class="mx-auto max-w-7xl space-y-6">
    <x-admin.page-header
        title="Hero Sections"
        description="Kelola banner utama yang tampil di halaman beranda.">
        <x-slot:actions>
            <a
                href="#"
                class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                + Add Hero Section
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.data-table>
        <x-slot:head>
            <th class="px-6 py-4">Title</th>
            <th class="px-6 py-4">Subtitle</th>
            <th class="px-6 py-4">Image</th>
            <th class="px-6 py-4">Order</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-right">Action</th>
        </x-slot:head>

        @forelse ($heroSections as $hero)
        <tr class="text-sm text-slate-700">
            <td class="px-6 py-4 font-semibold text-slate-900">
                {{ $hero->title }}
            </td>

            <td class="px-6 py-4">
                {{ $hero->subtitle ?? '-' }}
            </td>

            <td class="px-6 py-4">
                @if ($hero->image)
                <img
                    src="{{ asset('storage/' . $hero->image) }}"
                    alt="{{ $hero->title }}"
                    class="h-14 w-24 rounded-xl object-cover">
                @else
                <span class="text-slate-400">No image</span>
                @endif
            </td>

            <td class="px-6 py-4">
                {{ $hero->sort_order }}
            </td>

            <td class="px-6 py-4">
                @if ($hero->is_active)
                <x-admin.status-badge variant="completed">
                    Active
                </x-admin.status-badge>
                @else
                <x-admin.status-badge>
                    Inactive
                </x-admin.status-badge>
                @endif
            </td>

            <td class="px-6 py-4 text-right">
                <a
                    href="#"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-4 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-200">
                    Edit
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">
                Belum ada hero section.
            </td>
        </tr>
        @endforelse
    </x-admin.data-table>
</section>
@endsection