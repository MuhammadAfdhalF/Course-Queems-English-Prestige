<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Platform</th>
        <th class="px-6 py-4">Label</th>
        <th class="px-6 py-4">URL</th>
        <th class="px-6 py-4">Icon</th>
        <th class="px-6 py-4">Order</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($socialLinks as $socialLink)
    @php
    $editPayload = [
    'id' => $socialLink->id,
    'platform' => $socialLink->platform,
    'label' => $socialLink->label,
    'url' => $socialLink->url,
    'icon' => $socialLink->icon,
    'sort_order' => $socialLink->sort_order,
    'is_active' => (bool) $socialLink->is_active,
    'update_url' => route('admin.cms.contact.social-links.update', $socialLink),
    ];

    $deletePayload = [
    'id' => $socialLink->id,
    'title' => $socialLink->label ?: ucfirst($socialLink->platform),
    'delete_url' => route('admin.cms.contact.social-links.destroy', $socialLink),
    ];
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="px-6 py-4">
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold capitalize text-slate-700">
                {{ $socialLink->platform }}
            </span>
        </td>

        <td class="px-6 py-4 font-semibold text-slate-900">
            {{ $socialLink->label ?? '-' }}
        </td>

        <td class="max-w-md px-6 py-4">
            <a
                href="{{ $socialLink->url }}"
                target="_blank"
                class="line-clamp-1 font-medium text-blue-700 hover:underline">
                {{ $socialLink->url }}
            </a>
        </td>

        <td class="px-6 py-4">
            {{ $socialLink->icon ?? '-' }}
        </td>

        <td class="px-6 py-4">
            {{ $socialLink->sort_order }}
        </td>

        <td class="px-6 py-4">
            @if ($socialLink->is_active)
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
        colspan="7"
        title="No social links yet"
        description="Create your first social media link to display it on the contact page." />
    @endforelse
</x-admin.data-table>