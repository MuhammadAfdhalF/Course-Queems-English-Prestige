<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Question</th>
        <th class="px-6 py-4">Answer</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($faqs as $faq)
        @php
            $editPayload = [
                'id' => $faq->id,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'sort_order' => $faq->sort_order,
                'is_active' => (bool) $faq->is_active,
                'update_url' => route('admin.cms.faqs.update', $faq),
            ];

            $deletePayload = [
                'id' => $faq->id,
                'title' => $faq->question,
                'delete_url' => route('admin.cms.faqs.destroy', $faq),
            ];
        @endphp

        <tr class="text-sm text-slate-700">
            <td class="max-w-sm px-6 py-4 font-semibold text-slate-900">
                {{ $faq->question }}
            </td>

            <td class="max-w-xl px-6 py-4">
                <p class="line-clamp-2">
                    {{ $faq->answer ?? '-' }}
                </p>
            </td>

            <td class="px-6 py-4">
                {{ $faq->sort_order }}
            </td>

            <td class="px-6 py-4">
                @if ($faq->is_active)
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
        <tr>
            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">
                No FAQ data yet.
            </td>
        </tr>
    @endforelse
</x-admin.data-table>