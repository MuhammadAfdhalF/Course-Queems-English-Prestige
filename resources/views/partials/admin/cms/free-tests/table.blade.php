<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Free Test</th>
        <th class="px-6 py-4">Category</th>
        <th class="px-6 py-4">Duration</th>
        <th class="px-6 py-4">Questions</th>
        <th class="px-6 py-4">Result Mode</th>
        <th class="px-6 py-4">Total / Passing Score</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($freeTests as $freeTest)
    @php
    $resMode = $freeTest->result_mode?->value ?? (string) $freeTest->result_mode ?? 'score_only';
    $editPayload = [
        'id' => $freeTest->id,
        'title' => $freeTest->title,
        'free_test_category_id' => $freeTest->free_test_category_id,
        'category' => $freeTest->categoryRelation?->name ?? $freeTest->category,
        'description' => $freeTest->description,
        'duration_minutes' => $freeTest->duration_minutes,
        'result_mode' => $resMode,
        'total_score' => (float) $freeTest->total_score,
        'passing_score' => $freeTest->passing_score !== null ? (float) $freeTest->passing_score : null,
        'passing_grade' => $freeTest->passing_grade,
        'is_active' => (bool) $freeTest->is_active,
        'is_locked' => $freeTest->results()->exists(),
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

        <td class="px-6 py-4 font-medium capitalize">
            @if ($resMode === 'pass_fail')
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">Pass / Fail</span>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700">Score Only</span>
            @endif
        </td>

        <td class="px-6 py-4 text-xs font-semibold">
            Total: {{ (float) $freeTest->total_score }}
            @if ($resMode === 'pass_fail')
                <span class="block text-slate-400">Pass: {{ (float) $freeTest->passing_score }}</span>
            @endif
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
                    href="{{ route('admin.cms.free-tests.questions.index', $freeTest) }}"
                    title="Manage Questions"
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
        colspan="7"
        title="No free tests yet"
        description="Create your first free test for website visitors." />
    @endforelse
</x-admin.data-table>