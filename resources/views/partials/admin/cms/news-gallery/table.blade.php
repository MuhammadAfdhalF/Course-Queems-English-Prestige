<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Post</th>
        <th class="px-6 py-4">Type</th>
        <th class="px-6 py-4">Link</th>
        <th class="px-6 py-4">Published At</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($posts as $post)
    @php
    $thumbnailPayload = [
    'title' => $post->title,
    'url' => $post->thumbnail ? asset('storage/' . $post->thumbnail) : null,
    ];

    $editPayload = [
    'id' => $post->id,
    'title' => $post->title,
    'slug' => $post->slug,
    'type' => $post->type,
    'link_type' => $post->external_url ? 'external' : 'internal',
    'external_url' => $post->external_url,
    'thumbnail' => $post->thumbnail,
    'thumbnail_url' => $post->thumbnail ? asset('storage/' . $post->thumbnail) : null,
    'excerpt' => $post->excerpt,
    'content' => $post->content,
    'event_date' => $post->event_date?->format('Y-m-d'),
    'published_at' => $post->published_at?->format('Y-m-d\TH:i'),
    'is_published' => (bool) $post->is_published,
    'update_url' => route('admin.cms.news-gallery.update', $post),
    ];

    $deletePayload = [
    'id' => $post->id,
    'title' => $post->title,
    'delete_url' => route('admin.cms.news-gallery.destroy', $post),
    ];

    $imagePayload = [
    'id' => $post->id,
    'title' => $post->title,
    'type' => $post->type,
    'image_store_url' => route('admin.cms.news-gallery.images.store', $post),
    'images' => $post->images->map(fn ($image) => [
    'id' => $image->id,
    'image_url' => asset('storage/' . $image->image),
    'caption' => $image->caption,
    'sort_order' => $image->sort_order,
    'delete_url' => route('admin.cms.news-gallery.images.destroy', $image),
    ])->values(),
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="px-6 py-4">
            <div class="flex items-center gap-3">
                @if ($post->thumbnail)
                <button
                    type="button"
                    title="Preview Thumbnail"
                    @click='openImagePreview(@json($thumbnailPayload))'
                    class="group relative h-14 w-24 shrink-0 overflow-hidden rounded-xl">
                    <img
                        src="{{ asset('storage/' . $post->thumbnail) }}"
                        alt="{{ $post->title }}"
                        class="h-full w-full object-cover transition duration-200 group-hover:scale-105">

                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                        <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                    </span>
                </button>
                @else
                <div class="flex h-14 w-24 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                    <x-admin.icon name="image" class="h-5 w-5" />
                </div>
                @endif

                <div class="min-w-0">
                    <p class="font-semibold text-slate-900">
                        {{ $post->title }}
                    </p>
                    <p class="mt-1 line-clamp-1 text-xs text-slate-400">
                        {{ $post->slug }}
                    </p>
                </div>
            </div>
        </td>

        <td class="px-6 py-4">
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold capitalize text-slate-700">
                {{ $post->type }}
            </span>
        </td>

        <td class="px-6 py-4">
            @if ($post->external_url)
            <a
                href="{{ $post->external_url }}"
                target="_blank"
                class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 hover:bg-blue-100">
                External
            </a>
            @else
            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                Internal
            </span>
            @endif
        </td>

        <td class="px-6 py-4">
            {{ $post->published_at?->format('d M Y H:i') ?? '-' }}
        </td>

        <td class="px-6 py-4">
            @if ($post->is_published)
            <x-admin.status-badge variant="completed">
                Published
            </x-admin.status-badge>
            @else
            <x-admin.status-badge>
                Draft
            </x-admin.status-badge>
            @endif
        </td>

        <td class="px-6 py-4">
            <div class="flex justify-center gap-2">
                <button
                    type="button"
                    title="Manage Images"
                    @click='openImageModal(@json($imagePayload))'
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700 transition hover:bg-blue-100">
                    <x-admin.icon name="image" class="h-4 w-4" />
                </button>

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
        title="No posts yet"
        description="Create your first news or gallery post for the website." />
    @endforelse
</x-admin.data-table>