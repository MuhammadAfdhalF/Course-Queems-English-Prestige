<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Free Test</th>
        <th class="px-6 py-4">Category</th>
        <th class="px-6 py-4">Duration</th>
        <th class="px-6 py-4">Questions</th>
        <th class="px-6 py-4">Passing Grade</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($freeTests as $freeTest)
    @php
    $editPayload = [
    'id' => $freeTest->id,
    'title' => $freeTest->title,
    'free_test_category_id' => $freeTest->free_test_category_id,
    'category' => $freeTest->categoryRelation?->name ?? $freeTest->category,
    'description' => $freeTest->description,
    'duration_minutes' => $freeTest->duration_minutes,
    'passing_grade' => $freeTest->passing_grade,
    'is_active' => (bool) $freeTest->is_active,
    'update_url' => route('admin.cms.free-tests.update', $freeTest),
    ];

    $deletePayload = [
    'id' => $freeTest->id,
    'title' => $freeTest->title,
    'delete_url' => route('admin.cms.free-tests.destroy', $freeTest),
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="max-w-md px-6 py-4">
            <p class="font-semibold text-slate-900">
                {{ $freeTest->title }}
            </p>

            <p class="mt-1 line-clamp-2 text-xs text-slate-400">
                {{ $freeTest->description ?? 'No description' }}
            </p>
        </td>

        <td class="px-6 py-4">
            {{ $freeTest->categoryRelation?->name ?? $freeTest->category ?? '-' }}
        </td>

        <td class="px-6 py-4">
            {{ $freeTest->duration_minutes ? $freeTest->duration_minutes . ' minutes' : '-' }}
        </td>

        <td class="px-6 py-4">
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                {{ $freeTest->questions_count }} questions
            </span>
        </td>

        <td class="px-6 py-4">
            {{ $freeTest->passing_grade ? $freeTest->passing_grade . '%' : '-' }}
        </td>

        <td class="px-6 py-4">
            @if ($freeTest->is_active)
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
                    title="Manage Questions"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700 transition hover:bg-blue-100">
                    <x-admin.icon name="question" class="h-4 w-4" />
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
        title="No free tests yet"
        description="Create your first free test for website visitors." />
    @endforelse
</x-admin.data-table>