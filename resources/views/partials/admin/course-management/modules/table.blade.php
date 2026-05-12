<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Module</th>
        <th class="px-6 py-4">Contents</th>
        <th class="px-6 py-4">Preview</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="w-[220px] px-4 py-4 text-center">Actions</th>
    </x-slot:head>

    @forelse ($modules as $module)
    @php
    $editPayload = [
    'id' => $module->id,
    'title' => $module->title,
    'slug' => $module->slug,
    'short_description' => $module->short_description,
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

    $moduleNumber = str_pad((string) $module->sort_order, 2, '0', STR_PAD_LEFT);
    @endphp

    <tr class="text-sm text-slate-700 transition hover:bg-slate-50/80">
        <td class="max-w-xl px-6 py-5">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex rounded-md bg-slate-100 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                        {{ $moduleNumber }}
                    </span>

                    <p class="text-base font-extrabold text-slate-900">
                        {{ $module->title }}
                    </p>
                </div>
            </div>
        </td>

        <td class="px-6 py-5">
            <div class="flex flex-wrap items-center gap-2">
                <a
                    href="{{ route('admin.course-management.modules.materials.index', $module) }}"
                    class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 transition hover:bg-blue-100"
                    title="Manage Materials">
                    <x-admin.icon name="materials" class="h-3.5 w-3.5" />
                    {{ $module->materials_count }} materials
                </a>

                <a
                    href="{{ route('admin.course-management.modules.practice.index', $module) }}"
                    class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 transition hover:bg-amber-100"
                    title="Manage Practice">
                    <x-admin.icon name="practice" class="h-3.5 w-3.5" />
                    {{ $module->practices_count }} practices
                </a>
            </div>
        </td>

        <td class="px-6 py-5">
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

        <td class="px-6 py-5">
            <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-xl bg-slate-100 px-3 text-sm font-extrabold text-slate-700">
                {{ $module->sort_order }}
            </span>
        </td>

        <td class="px-6 py-5">
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

        <td class="px-4 py-4 align-middle">
            <div class="ml-auto grid w-full max-w-[210px] grid-cols-2 gap-2">
                <a
                    href="{{ route('admin.course-management.modules.materials.index', $module) }}"
                    title="Manage Materials"
                    class="inline-flex h-9 items-center justify-center gap-2 rounded-lg bg-blue-50 px-3 text-xs font-semibold text-blue-700 transition hover:bg-blue-100">
                    <x-admin.icon name="materials" class="h-5 w-5 shrink-0" />
                    <span>Materials</span>
                </a>

                <a
                    href="{{ route('admin.course-management.modules.practice.index', $module) }}"
                    title="Manage Practice"
                    class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-amber-50 px-3 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">
                    <x-admin.icon name="check" class="h-3.5 w-3.5" />
                    <span>Practice</span>
                </a>

                <button
                    type="button"
                    title="Edit"
                    @click='openEditModal(@json($editPayload))'
                    class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-slate-100 px-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-200">
                    <x-admin.icon name="pencil" class="h-3.5 w-3.5" />
                    <span>Edit</span>
                </button>

                <button
                    type="button"
                    title="Delete"
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
        colspan="6"
        title="No modules yet"
        description="Create the first learning module for this course level." />
    @endforelse
</x-admin.data-table>