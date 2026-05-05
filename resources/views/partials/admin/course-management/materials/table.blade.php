<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Material</th>
        <th class="px-6 py-4">Type</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($materials as $material)
    @php
    $deletePayload = [
    'id' => $material->id,
    'title' => $material->title,
    'delete_url' => route('admin.course-management.materials.destroy', $material),
    ];

    $fileUrl = $material->file_path ? asset('storage/' . $material->file_path) : null;
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="max-w-2xl px-6 py-4">
            <div class="flex items-center gap-3">
                @if ($material->material_type === 'image' && $fileUrl)
                <button
                    type="button"
                    @click='openImagePreview(@json([
                                "title" => $material->title,
                                "url" => $fileUrl,
                            ]))'
                    class="group relative h-14 w-24 shrink-0 overflow-hidden rounded-xl">
                    <img
                        src="{{ $fileUrl }}"
                        alt="{{ $material->title }}"
                        class="h-full w-full object-cover transition duration-200 group-hover:scale-105">

                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                        <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                    </span>
                </button>
                @else
                <div class="flex h-14 w-24 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                    @if ($material->material_type === 'text')
                    <x-admin.icon name="pencil" class="h-5 w-5" />
                    @elseif ($material->material_type === 'video')
                    <x-admin.icon name="play" class="h-5 w-5" />
                    @elseif ($material->material_type === 'audio')
                    <x-admin.icon name="volume" class="h-5 w-5" />
                    @elseif ($material->material_type === 'pdf')
                    <x-admin.icon name="file" class="h-5 w-5" />
                    @else
                    <x-admin.icon name="file" class="h-5 w-5" />
                    @endif
                </div>
                @endif

                <div class="min-w-0">
                    <p class="font-semibold text-slate-900">
                        {{ $material->title }}
                    </p>

                    @if ($material->material_type === 'text')
                    <p class="mt-1 line-clamp-2 text-xs text-slate-500">
                        {{ Str::limit(strip_tags($material->content), 120) ?: 'No content' }}
                    </p>
                    @elseif ($fileUrl)
                    <a
                        href="{{ $fileUrl }}"
                        target="_blank"
                        class="mt-1 inline-flex text-xs font-semibold text-blue-700 hover:underline">
                        Open file
                    </a>
                    @else
                    <p class="mt-1 text-xs text-slate-400">
                        No file uploaded
                    </p>
                    @endif
                </div>
            </div>
        </td>

        <td class="px-6 py-4">
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold capitalize text-slate-700">
                {{ str_replace('_', ' ', $material->material_type) }}
            </span>
        </td>

        <td class="px-6 py-4">
            {{ $material->sort_order }}
        </td>

        <td class="px-6 py-4">
            @if ($material->is_active)
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
                <a
                    href="{{ route('admin.course-management.materials.edit', $material) }}"
                    title="Edit"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-700 transition hover:bg-slate-200">
                    <x-admin.icon name="pencil" class="h-4 w-4" />
                </a>

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
        colspan="5"
        title="No materials yet"
        description="Create the first material block for this module." />
    @endforelse
</x-admin.data-table>