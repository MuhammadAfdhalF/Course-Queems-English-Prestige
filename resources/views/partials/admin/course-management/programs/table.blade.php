<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Program</th>
        <th class="px-6 py-4">Levels</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($coursePrograms as $program)
    @php
    $editPayload = [
    'id' => $program->id,
    'name' => $program->name,
    'slug' => $program->slug,
    'sort_order' => $program->sort_order,
    'is_active' => (bool) $program->is_active,
    'update_url' => route('admin.course-management.programs.update', $program),
    ];

    $deletePayload = [
    'id' => $program->id,
    'title' => $program->name,
    'delete_url' => route('admin.course-management.programs.destroy', $program),
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="max-w-md px-6 py-4">
            <p class="font-semibold text-slate-900">
                {{ $program->name }}
            </p>

            <p class="mt-1 line-clamp-1 text-xs text-slate-400">
                {{ $program->slug }}
            </p>
        </td>

        <td class="px-6 py-4">
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                {{ $program->course_levels_count }} levels
            </span>
        </td>

        <td class="px-6 py-4">
            {{ $program->sort_order }}
        </td>

        <td class="px-6 py-4">
            @if ($program->is_active)
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
                    title="Manage Levels"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700 transition hover:bg-blue-100">
                    <x-admin.icon name="eye" class="h-4 w-4" />
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
        colspan="5"
        title="No course programs yet"
        description="Create your first course program, such as General English, TOEFL, or IELTS." />
    @endforelse
</x-admin.data-table>