<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Question</th>
        <th class="px-6 py-4">Correct Answer</th>
        <th class="px-6 py-4">Score</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($questions as $question)
    @php
    $editPayload = [
    'id' => $question->id,
    'question' => $question->question,
    'option_a' => $question->option_a,
    'option_b' => $question->option_b,
    'option_c' => $question->option_c,
    'option_d' => $question->option_d,
    'correct_answer' => $question->correct_answer,
    'score' => $question->score,
    'sort_order' => $question->sort_order,
    'is_active' => (bool) $question->is_active,
    'update_url' => route('admin.cms.free-tests.questions.update', $question),
    ];

    $deletePayload = [
    'id' => $question->id,
    'title' => 'Question #' . $question->sort_order,
    'delete_url' => route('admin.cms.free-tests.questions.destroy', $question),
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="max-w-2xl px-6 py-4">
            <p class="font-semibold text-slate-900">
                {{ $question->question }}
            </p>

            <div class="mt-3 grid gap-2 text-xs text-slate-500 md:grid-cols-2">
                <p><span class="font-bold">A.</span> {{ $question->option_a }}</p>
                <p><span class="font-bold">B.</span> {{ $question->option_b }}</p>
                <p><span class="font-bold">C.</span> {{ $question->option_c }}</p>
                <p><span class="font-bold">D.</span> {{ $question->option_d }}</p>
            </div>
        </td>

        <td class="px-6 py-4">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-sm font-bold text-emerald-700">
                {{ $question->correct_answer }}
            </span>
        </td>

        <td class="px-6 py-4">
            {{ $question->score }}
        </td>

        <td class="px-6 py-4">
            {{ $question->sort_order }}
        </td>

        <td class="px-6 py-4">
            @if ($question->is_active)
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
        colspan="6"
        title="No questions yet"
        description="Create your first multiple-choice question for this free test." />
    @endforelse
</x-admin.data-table>