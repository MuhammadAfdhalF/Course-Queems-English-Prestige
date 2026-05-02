<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Title</th>
        <th class="px-6 py-4">Description</th>
        <th class="px-6 py-4">Icon</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($whyChooseUsItems as $item)
    @php
    $editPayload = [
    'id' => $item->id,
    'title' => $item->title,
    'description' => $item->description,
    'icon' => $item->icon,
    'icon_url' => $item->icon ? asset('storage/' . $item->icon) : null,
    'sort_order' => $item->sort_order,
    'is_active' => (bool) $item->is_active,
    'update_url' => route('admin.cms.why-choose-us.update', $item),
    ];

    $deletePayload = [
    'id' => $item->id,
    'title' => $item->title,
    'delete_url' => route('admin.cms.why-choose-us.destroy', $item),
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="max-w-sm px-6 py-4 font-semibold text-slate-900">
            {{ $item->title }}
        </td>

        <td class="max-w-xl px-6 py-4">
            <p class="line-clamp-2">
                {{ $item->description ?? '-' }}
            </p>
        </td>

        <td class="px-6 py-4">
            @if ($item->icon)
            <button
                type="button"
                title="Preview Icon"
                @click='openImagePreview(@json([
                            "title" => $item->title,
                            "url" => asset("storage/" . $item->icon),
                        ]))'
                class="group relative overflow-hidden rounded-xl">
                <img
                    src="{{ asset('storage/' . $item->icon) }}"
                    alt="{{ $item->title }}"
                    class="h-14 w-14 rounded-xl object-cover transition duration-200 group-hover:scale-105">

                <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                    <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                </span>
            </button>
            @else
            <span class="text-slate-400">No icon</span>
            @endif
        </td>

        <td class="px-6 py-4">
            {{ $item->sort_order }}
        </td>

        <td class="px-6 py-4">
            @if ($item->is_active)
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
    <x-admin.empty-state
        colspan="6"
        title="No Why Choose Us items yet"
        description="Create your first item to display it on the About page." />
    @endforelse
</x-admin.data-table>