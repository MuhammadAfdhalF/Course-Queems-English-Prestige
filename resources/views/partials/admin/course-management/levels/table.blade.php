<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Level</th>
        <th class="px-6 py-4">Price</th>
        <th class="px-6 py-4">Mode</th>
        <th class="px-6 py-4">Access</th>
        <th class="px-6 py-4">Modules</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="w-[220px] px-4 py-4 text-center">Actions</th>
    </x-slot:head>

    @forelse ($courseLevels as $level)
    @php
    $thumbnailUrl = match ($level->thumbnail_type) {
        'video' => $level->video_poster_file ? asset('storage/' . $level->video_poster_file) : null,
        default => $level->thumbnail_file ? asset('storage/' . $level->thumbnail_file) : null,
    };

    $thumbnailPayload = [
        'title' => $level->name,
        'url' => $thumbnailUrl,
    ];

    $deletePayload = [
        'id' => $level->id,
        'title' => $level->name,
        'delete_url' => route('admin.course-management.levels.destroy', $level),
    ];

    $learningModeLabel = match ($level->learning_mode) {
        'offline' => 'Offline',
        'hybrid' => 'Hybrid',
        default => 'Online',
    };

    $learningModeClass = match ($level->learning_mode) {
        'offline' => 'bg-slate-100 text-slate-700',
        'hybrid' => 'bg-amber-50 text-amber-700',
        default => 'bg-emerald-50 text-emerald-700',
    };
    @endphp

    <tr class="text-sm text-slate-700 transition hover:bg-slate-50/80">
        <td class="max-w-lg px-6 py-5">
            <div class="flex items-center gap-3">
                @if ($level->thumbnail_file && $level->thumbnail_type === 'image')
                <button
                    type="button"
                    title="Preview Thumbnail"
                    @click='openImagePreview(@json($thumbnailPayload))'
                    class="group relative h-14 w-24 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                    <img
                        src="{{ asset('storage/' . $level->thumbnail_file) }}"
                        alt="{{ $level->name }}"
                        class="h-full w-full object-cover transition duration-200 group-hover:scale-105">

                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                        <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                    </span>
                </button>
                @elseif ($level->thumbnail_type === 'video')
                <button
                    type="button"
                    title="Preview Video Poster"
                    @click='openImagePreview(@json($thumbnailPayload))'
                    class="group relative flex h-14 w-24 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-900 text-white">
                    @if ($level->video_poster_file)
                    <img
                        src="{{ asset('storage/' . $level->video_poster_file) }}"
                        alt="{{ $level->name }}"
                        class="h-full w-full object-cover opacity-80 transition duration-200 group-hover:scale-105 group-hover:opacity-100">
                    @endif
                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/30 text-white transition group-hover:bg-slate-900/50">
                        <x-admin.icon name="play" class="h-5 w-5" />
                    </span>
                </button>
                @else
                <div class="flex h-14 w-24 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                    <x-admin.icon name="image" class="h-5 w-5" />
                </div>
                @endif

                <div class="min-w-0">
                    <p class="font-extrabold text-slate-900">
                        {{ $level->name }}
                    </p>

                    <p class="mt-1 line-clamp-1 text-xs font-semibold text-slate-400">
                        {{ $level->slug }}
                    </p>

                    <p class="mt-1 line-clamp-1 text-xs leading-5 text-slate-500">
                        {{ $level->short_description ?? 'No short description' }}
                    </p>
                </div>
            </div>
        </td>

        <td class="px-6 py-5">
            <p class="font-semibold leading-5 text-slate-700">
                Rp<br>
                {{ number_format((float) $level->price, 0, ',', '.') }}
            </p>
        </td>

        <td class="px-6 py-5">
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $learningModeClass }}">
                {{ $learningModeLabel }}
            </span>
        </td>

        <td class="px-6 py-5">
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

        <td class="px-6 py-5">
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                {{ $level->modules_count }} modules
            </span>
        </td>

        <td class="px-6 py-5">
            <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-xl bg-slate-100 px-3 text-sm font-extrabold text-slate-700">
                {{ $level->sort_order }}
            </span>
        </td>

        <td class="px-6 py-5">
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

        <td class="px-4 py-5 align-middle">
            <div class="ml-auto grid w-full max-w-[210px] grid-cols-2 gap-2">
                <a
                    href="{{ route('admin.course-management.levels.modules.index', $level) }}"
                    title="Manage Modules"
                    class="inline-flex h-9 items-center justify-center gap-2 rounded-lg bg-blue-50 px-3 text-xs font-semibold text-blue-700 transition hover:bg-blue-100">
                    <x-admin.icon name="book" class="h-5 w-5 shrink-0" />
                    <span>Modules</span>
                </a>

                <a
                    href="{{ route('admin.course-management.levels.final-exam.index', $level) }}"
                    title="Manage Final Exam"
                    class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-amber-50 px-3 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">
                    <x-admin.icon name="practice" class="h-4 w-4" />
                    <span>Exam</span>
                </a>

                <a
                    href="{{ route('admin.course-management.levels.edit', $level) }}"
                    title="Edit Course Level"
                    class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-slate-100 px-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-200">
                    <x-admin.icon name="pencil" class="h-3.5 w-3.5" />
                    <span>Edit</span>
                </a>

                <button
                    type="button"
                    title="Delete Course Level"
                    @click='openDeleteModal(@json($deletePayload))'
                    class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-rose-50 px-3 text-xs font-semibold text-rose-600 transition hover:bg-rose-100">
                    <x-admin.icon name="trash" class="h-3.5 w-3.5" />
                    <span>Delete</span>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <x-admin.empty-state
        colspan="8"
        title="No course levels yet"
        description="Create the first level for this course program." />
    @endforelse
</x-admin.data-table>