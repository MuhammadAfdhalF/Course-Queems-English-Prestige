<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Level</th>
        <th class="px-6 py-4">Price</th>
        <th class="px-6 py-4">Access</th>
        <th class="px-6 py-4">Modules</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($courseLevels as $level)
    @php
    $thumbnailPayload = [
    'title' => $level->name,
    'url' => $level->thumbnail_file ? asset('storage/' . $level->thumbnail_file) : null,
    ];

    $deletePayload = [
    'id' => $level->id,
    'title' => $level->name,
    'delete_url' => route('admin.course-management.levels.destroy', $level),
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="max-w-lg px-6 py-4">
            <div class="flex items-center gap-3">
                @if ($level->thumbnail_file && $level->thumbnail_type === 'image')
                <button
                    type="button"
                    title="Preview Thumbnail"
                    @click='openImagePreview(@json($thumbnailPayload))'
                    class="group relative h-14 w-24 shrink-0 overflow-hidden rounded-xl">
                    <img
                        src="{{ asset('storage/' . $level->thumbnail_file) }}"
                        alt="{{ $level->name }}"
                        class="h-full w-full object-cover transition duration-200 group-hover:scale-105">

                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                        <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                    </span>
                </button>
                @elseif ($level->thumbnail_file && $level->thumbnail_type === 'video')
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
                        {{ $level->name }}
                    </p>

                    <p class="mt-1 line-clamp-1 text-xs text-slate-400">
                        {{ $level->slug }}
                    </p>

                    <p class="mt-1 line-clamp-1 text-xs text-slate-500">
                        {{ $level->short_description ?? 'No short description' }}
                    </p>
                </div>
            </div>
        </td>

        <td class="px-6 py-4">
            Rp {{ number_format((float) $level->price, 0, ',', '.') }}
        </td>

        <td class="px-6 py-4">
            @if ($level->access_type === 'lifetime')
            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                Lifetime
            </span>
            @else
            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                {{ $level->access_duration_days }} days
            </span>
            @endif
        </td>

        <td class="px-6 py-4">
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                {{ $level->modules_count }} modules
            </span>
        </td>

        <td class="px-6 py-4">
            {{ $level->sort_order }}
        </td>

        <td class="px-6 py-4">
            @if ($level->is_active)
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
                    href="#"
                    title="Manage Modules"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700 transition hover:bg-blue-100">
                    <x-admin.icon name="eye" class="h-4 w-4" />
                </a>

                <a
                    href="{{ route('admin.course-management.levels.edit', $level) }}"
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
        colspan="7"
        title="No course levels yet"
        description="Create the first level for this course program." />
    @endforelse
</x-admin.data-table>