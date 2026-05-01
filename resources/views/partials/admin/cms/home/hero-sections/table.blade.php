<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Title</th>
        <th class="px-6 py-4">Subtitle</th>
        <th class="px-6 py-4">Image</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($heroSections as $hero)
    @php
    $editPayload = [
    'id' => $hero->id,
    'title' => $hero->title,
    'subtitle' => $hero->subtitle,
    'image' => $hero->image,
    'image_url' => $hero->image ? asset('storage/' . $hero->image) : null,
    'sort_order' => $hero->sort_order,
    'is_active' => (bool) $hero->is_active,
    'update_url' => route('admin.cms.hero-sections.update', $hero),
    ];

    $deletePayload = [
    'id' => $hero->id,
    'title' => $hero->title,
    'delete_url' => route('admin.cms.hero-sections.destroy', $hero),
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="px-6 py-4 font-semibold text-slate-900">
            {{ $hero->title }}
        </td>

        <td class="px-6 py-4">
            {{ $hero->subtitle ?? '-' }}
        </td>

        <td class="px-6 py-4">
            @if ($hero->image)
            <button
                type="button"
                title="Preview Image"
                @click='openImagePreview(@json([
            "title" => $hero->title,
            "url" => asset("storage/" . $hero->image),
        ]))'
                class="group relative overflow-hidden rounded-xl">
                <img
                    src="{{ asset('storage/' . $hero->image) }}"
                    alt="{{ $hero->title }}"
                    class="h-14 w-24 rounded-xl object-cover transition duration-200 group-hover:scale-105">

                <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                    <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                </span>
            </button>
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

        <td class="px-6 py-4">
            <div class="flex justify-center gap-2">
                <button
                    type="button"
                    title="Edit"
                    @click='openEditModal(@json($editPayload))'
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-700 transition hover:bg-slate-200">
                    <x-admin.icon name="pencil" class="h-4 w-4" />
                </button>

                <button
                    type="button"
                    title="Delete"
                    @click='openDeleteModal(@json($deletePayload))'
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100">
                    <x-admin.icon name="trash" class="h-4 w-4" />
                </button>
            </div>
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