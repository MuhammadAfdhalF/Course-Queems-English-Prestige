<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Title</th>
        <th class="px-6 py-4">Description</th>
        <th class="px-6 py-4">Thumbnail</th>
        <th class="px-6 py-4">Video</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($profileVideos as $video)
    @php
    $editPayload = [
    'id' => $video->id,
    'title' => $video->title,
    'description' => $video->description,
    'video_file' => $video->video_file,
    'video_url' => $video->video_file ? asset('storage/' . $video->video_file) : null,
    'thumbnail' => $video->thumbnail,
    'thumbnail_url' => $video->thumbnail ? asset('storage/' . $video->thumbnail) : null,
    'sort_order' => $video->sort_order,
    'is_active' => (bool) $video->is_active,
    'update_url' => route('admin.cms.profile-videos.update', $video),
    ];

    $deletePayload = [
    'id' => $video->id,
    'title' => $video->title,
    'delete_url' => route('admin.cms.profile-videos.destroy', $video),
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="max-w-sm px-6 py-4 font-semibold text-slate-900">
            {{ $video->title }}
        </td>

        <td class="max-w-md px-6 py-4">
            <p class="line-clamp-2">
                {{ $video->description ?? '-' }}
            </p>
        </td>

        <td class="px-6 py-4">
            @if ($video->thumbnail)
            <button
                type="button"
                title="Preview Thumbnail"
                @click='openImagePreview(@json([
                            "title" => $video->title,
                            "url" => asset("storage/" . $video->thumbnail),
                        ]))'
                class="group relative overflow-hidden rounded-xl">
                <img
                    src="{{ asset('storage/' . $video->thumbnail) }}"
                    alt="{{ $video->title }}"
                    class="h-14 w-24 rounded-xl object-cover transition duration-200 group-hover:scale-105">

                <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                    <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                </span>
            </button>
            @else
            <span class="text-slate-400">No thumbnail</span>
            @endif
        </td>

        <td class="px-6 py-4">
            @if ($video->video_file)
            <a
                href="{{ asset('storage/' . $video->video_file) }}"
                target="_blank"
                class="inline-flex items-center justify-center rounded-xl bg-blue-50 px-4 py-2 text-sm font-bold text-blue-700 transition hover:bg-blue-100">
                View Video
            </a>
            @else
            <span class="text-slate-400">No video</span>
            @endif
        </td>

        <td class="px-6 py-4">
            {{ $video->sort_order }}
        </td>

        <td class="px-6 py-4">
            @if ($video->is_active)
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
        title="No profile videos yet"
        description="Create your first profile video to display it on the About page." />
    @endforelse
</x-admin.data-table>