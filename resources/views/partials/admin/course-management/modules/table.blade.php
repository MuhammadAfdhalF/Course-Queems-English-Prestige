<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Module</th>
        <th class="px-6 py-4">Materials</th>
        <th class="px-6 py-4">Practices</th>
        <th class="px-6 py-4">Preview</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($modules as $module)
    @php
    $mediaPayload = [
    'title' => $module->title,
    'url' => $module->opening_media_file ? asset('storage/' . $module->opening_media_file) : null,
    ];

    $editPayload = [
    'id' => $module->id,
    'title' => $module->title,
    'slug' => $module->slug,
    'short_description' => $module->short_description,
    'opening_media_type' => $module->opening_media_type,
    'opening_media_file' => $module->opening_media_file,
    'opening_media_url' => $module->opening_media_file ? asset('storage/' . $module->opening_media_file) : null,
    'sort_order' => $module->sort_order,
    'is_preview' => (bool) $module->is_preview,
    'is_active' => (bool) $module->is_active,
    'update_url' => route('admin.course-management.modules.update', $module),
    ];

    $deletePayload = [
    'id' => $module->id,
    'title' => $module->title,
    'delete_url' => route('admin.course-management.modules.destroy', $module),
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="max-w-xl px-6 py-4">
            <div class="flex items-center gap-3">
                @if ($module->opening_media_file && $module->opening_media_type === 'image')
                <button
                    type="button"
                    title="Preview Opening Image"
                    @click='openImagePreview(@json($mediaPayload))'
                    class="group relative h-14 w-24 shrink-0 overflow-hidden rounded-xl">
                    <img
                        src="{{ asset('storage/' . $module->opening_media_file) }}"
                        alt="{{ $module->title }}"
                        class="h-full w-full object-cover transition duration-200 group-hover:scale-105">

                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                        <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                    </span>
                </button>
                @elseif ($module->opening_media_file && $module->opening_media_type === 'video')
                <div class="flex h-14 w-24 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-white">
                    <x-admin.icon name="play" class="h-5 w-5" />
                </div>
                @else
                <div class="flex h-14 w-24 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                    <x-admin.icon name="image" class="h-5 w-5" />
                </div>
                @endif

                <div class="min-w-0">
                    <p class="font-semibold text-slate-900">
                        {{ $module->title }}
                    </p>

                    <p class="mt-1 line-clamp-1 text-xs text-slate-400">
                        {{ $module->slug }}
                    </p>

                    <p class="mt-1 line-clamp-1 text-xs text-slate-500">
                        {{ $module->short_description ?? 'No short description' }}
                    </p>
                </div>
            </div>
        </td>

        <td class="px-6 py-4">
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                {{ $module->materials_count }} materials
            </span>
        </td>

        <td class="px-6 py-4">
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                {{ $module->practices_count }} practices
            </span>
        </td>

        <td class="px-6 py-4">
            @if ($module->is_preview)
            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                Preview
            </span>
            @else
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                Locked
            </span>
            @endif
        </td>

        <td class="px-6 py-4">
            {{ $module->sort_order }}
        </td>

        <td class="px-6 py-4">
            @if ($module->is_active)
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
                    href="{{ route('admin.course-management.modules.materials.index', $module) }}"
                    title="Manage Materials"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700 transition hover:bg-blue-100">
                    <x-admin.icon name="eye" class="h-4 w-4" />
                </a>

                <a
                    href="{{ route('admin.course-management.modules.practice.index', $module) }}"
                    title="Manage Practice"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700 transition hover:bg-amber-100">
                    <x-admin.icon name="check" class="h-4 w-4" />
                </a>

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
        title="No modules yet"
        description="Create the first learning module for this course level." />
    @endforelse
</x-admin.data-table>