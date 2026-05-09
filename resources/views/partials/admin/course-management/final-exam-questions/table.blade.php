<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Question</th>
        <th class="px-6 py-4">Type</th>
        <th class="px-6 py-4">Score</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($questions as $question)
    @php
    $deletePayload = [
    'id' => $question->id,
    'title' => 'Question #' . $question->sort_order,
    'delete_url' => route('admin.course-management.final-exam-questions.destroy', $question),
    ];

    $typeLabels = [
    'multiple_choice' => 'Multiple Choice',
    'short_answer' => 'Short Answer',
    'essay' => 'Essay',
    'upload' => 'Upload File',
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="max-w-2xl px-6 py-4">
            <p class="line-clamp-2 font-semibold text-slate-900">
                {{ Str::limit(strip_tags($question->question), 150) }}
            </p>

            @if ($question->question_type === 'multiple_choice')
            <div class="mt-3 grid gap-1 text-xs text-slate-500 sm:grid-cols-2">
                @foreach ($question->options as $option)
                <p @class([ 'font-bold text-emerald-700'=> $option->is_correct,
                    ])>
                    {{ $option->option_label }}. {{ Str::limit($option->option_text, 60) }}
                    @if ($option->is_correct)
                    ✓
                    @endif
                </p>
                @endforeach
            </div>
            @endif
        </td>

        <td class="px-6 py-4">
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                {{ $typeLabels[$question->question_type] ?? $question->question_type }}
            </span>
        </td>

        <td class="px-6 py-4">
            {{ number_format((float) $question->score, 2) }}
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
                <a
                    href="{{ route('admin.course-management.final-exam-questions.edit', $question) }}"
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
        colspan="6"
        title="No questions yet"
        description="Create the first question for this final exam." />
    @endforelse
</x-admin.data-table>