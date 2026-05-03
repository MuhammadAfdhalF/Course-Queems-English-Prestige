<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Category</th>
        <th class="px-6 py-4">Description</th>
        <th class="px-6 py-4">Tests</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($categories as $category)
    @php
    $editPayload = [
    'id' => $category->id,
    'name' => $category->name,
    'slug' => $category->slug,
    'description' => $category->description,
    'sort_order' => $category->sort_order,
    'is_active' => (bool) $category->is_active,
    'update_url' => route('admin.cms.free-test-categories.update', $category),
    ];

    $deletePayload = [
    'id' => $category->id,
    'title' => $category->name,
    'delete_url' => route('admin.cms.free-test-categories.destroy', $category),
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="max-w-sm px-6 py-4">
            <p class="font-semibold text-slate-900">
                {{ $category->name }}
            </p>
            <p class="mt-1 line-clamp-1 text-xs text-slate-400">
                {{ $category->slug }}
            </p>
        </td>

        <td class="max-w-md px-6 py-4">
            <p class="line-clamp-2">
                {{ $category->description ?? '-' }}
            </p>
        </td>

        <td class="px-6 py-4">
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                {{ $category->free_tests_count }} tests
            </span>
        </td>

        <td class="px-6 py-4">
            {{ $category->sort_order }}
        </td>

        <td class="px-6 py-4">
            @if ($category->is_active)
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
        title="No categories yet"
        description="Create your first free test category." />
    @endforelse
</x-admin.data-table>