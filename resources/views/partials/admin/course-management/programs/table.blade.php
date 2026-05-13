<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Program</th>
        <th class="px-6 py-4">Levels</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="w-[190px] px-6 py-4 text-center">Action</th>
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

    <tr class="text-sm text-slate-700 transition hover:bg-slate-50/80">
        <td class="max-w-md px-6 py-5">
            <p class="text-base font-extrabold text-slate-900">
                {{ $program->name }}
            </p>

            <p class="mt-1 line-clamp-1 text-xs font-semibold text-slate-400">
                {{ $program->slug }}
            </p>
        </td>

        <td class="px-6 py-5">
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                {{ $program->course_levels_count }} levels
            </span>
        </td>

        <td class="px-6 py-5">
            <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-xl bg-slate-100 px-3 text-sm font-extrabold text-slate-700">
                {{ $program->sort_order }}
            </span>
        </td>

        <td class="px-6 py-5">
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

        <td class="px-6 py-5 align-middle">
            <div class="mx-auto grid w-full max-w-[170px] gap-2">
                <a
                    href="{{ route('admin.course-management.programs.levels.index', $program) }}"
                    title="Manage Levels"
                    class="inline-flex h-9 w-full items-center justify-center gap-2 rounded-lg bg-blue-50 px-3 text-xs font-semibold text-blue-700 transition hover:bg-blue-100">
                    <x-admin.icon name="book" class="h-4.5 w-4.5 shrink-0" />
                    <span>Levels</span>
                </a>

                <div class="grid grid-cols-2 gap-2">
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