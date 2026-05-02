<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Mentor</th>
        <th class="px-6 py-4">Position</th>
        <th class="px-6 py-4">Expertise</th>
        <th class="px-6 py-4">Description</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($mentors as $mentor)
    @php
    $editPayload = [
    'id' => $mentor->id,
    'name' => $mentor->name,
    'photo' => $mentor->photo,
    'photo_url' => $mentor->photo ? asset('storage/' . $mentor->photo) : null,
    'position' => $mentor->position,
    'expertise' => $mentor->expertise,
    'description' => $mentor->description,
    'sort_order' => $mentor->sort_order,
    'is_active' => (bool) $mentor->is_active,
    'update_url' => route('admin.cms.mentors.update', $mentor),
    ];

    $deletePayload = [
    'id' => $mentor->id,
    'title' => $mentor->name,
    'delete_url' => route('admin.cms.mentors.destroy', $mentor),
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="px-6 py-4">
            <div class="flex items-center gap-3">
                @if ($mentor->photo)
                <button
                    type="button"
                    title="Preview Photo"
                    @click='openImagePreview(@json([
                                "title" => $mentor->name,
                                "url" => asset("storage/" . $mentor->photo),
                            ]))'
                    class="group relative h-12 w-12 shrink-0 overflow-hidden rounded-xl">
                    <img
                        src="{{ asset('storage/' . $mentor->photo) }}"
                        alt="{{ $mentor->name }}"
                        class="h-full w-full object-cover transition duration-200 group-hover:scale-105">

                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                        <x-admin.icon name="eye" class="h-4 w-4 opacity-0 transition group-hover:opacity-100" />
                    </span>
                </button>
                @else
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-sm font-bold text-slate-500">
                    {{ strtoupper(substr($mentor->name, 0, 1)) }}
                </div>
                @endif

                <div>
                    <p class="font-semibold text-slate-900">
                        {{ $mentor->name }}
                    </p>
                </div>
            </div>
        </td>

        <td class="px-6 py-4">
            {{ $mentor->position ?? '-' }}
        </td>

        <td class="px-6 py-4">
            {{ $mentor->expertise ?? '-' }}
        </td>

        <td class="max-w-md px-6 py-4">
            <p class="line-clamp-2">
                {{ $mentor->description ?? '-' }}
            </p>
        </td>

        <td class="px-6 py-4">
            {{ $mentor->sort_order }}
        </td>

        <td class="px-6 py-4">
            @if ($mentor->is_active)
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
        colspan="7"
        title="No mentors yet"
        description="Create your first mentor profile to display it on the About page." />
    @endforelse
</x-admin.data-table>