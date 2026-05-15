<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Template</th>
        <th class="px-6 py-4">Scope</th>
        <th class="px-6 py-4">Background</th>
        <th class="px-6 py-4">Default</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4">Usage</th>
        <th class="w-[150px] px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($templates as $template)
        @php
            $editPayload = [
                'id' => $template->id,
                'name' => $template->name,
                'course_program_id' => $template->course_program_id ? (string) $template->course_program_id : '',
                'background_image' => $template->background_image,
                'background_image_url' => $template->background_image ? asset('storage/' . $template->background_image) : null,
                'is_default' => (bool) $template->is_default,
                'is_active' => (bool) $template->is_active,
                'update_url' => route('admin.course-management.certificate-templates.update', $template),
            ];

            $deletePayload = [
                'id' => $template->id,
                'title' => $template->name,
                'delete_url' => route('admin.course-management.certificate-templates.destroy', $template),
            ];

            $isUsed = (int) $template->certificates_count > 0;
        @endphp

        <tr class="text-sm text-slate-700 transition hover:bg-slate-50/80">
            <td class="min-w-[240px] px-6 py-5">
                <p class="font-extrabold text-slate-900">
                    {{ $template->name }}
                </p>

                <p class="mt-1 text-xs font-semibold text-slate-400">
                    Created {{ $template->created_at?->format('d M Y') }}
                </p>
            </td>

            <td class="min-w-[210px] px-6 py-5">
                @if ($template->courseProgram)
                    <p class="font-bold text-slate-900">
                        {{ $template->courseProgram->name }}
                    </p>

                    <span class="mt-2 inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                        Program Specific
                    </span>
                @else
                    <p class="font-bold text-slate-900">
                        All Programs
                    </p>

                    <span class="mt-2 inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                        Global Template
                    </span>
                @endif
            </td>

            <td class="px-6 py-5">
                @if ($template->background_image)
                    <button
                        type="button"
                        title="Preview Background"
                        @click='openImagePreview(@json([
                            "title" => $template->name,
                            "url" => asset("storage/" . $template->background_image),
                        ]))'
                        class="group relative block overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                        <img
                            src="{{ asset('storage/' . $template->background_image) }}"
                            alt="{{ $template->name }}"
                            class="h-16 w-28 rounded-xl object-cover transition duration-200 group-hover:scale-105">

                        <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                            <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                        </span>
                    </button>
                @else
                    <div class="flex h-16 w-28 items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50">
                        <span class="text-xs font-bold text-slate-400">
                            No Image
                        </span>
                    </div>
                @endif
            </td>

            <td class="px-6 py-5">
                @if ($template->is_default)
                    <span class="inline-flex rounded-full bg-yellow-50 px-3 py-1 text-xs font-extrabold uppercase tracking-[0.12em] text-[var(--color-brand-gold)]">
                        Default
                    </span>
                @else
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                        Normal
                    </span>
                @endif
            </td>

            <td class="px-6 py-5">
                @if ($template->is_active)
                    <x-admin.status-badge variant="completed">
                        Active
                    </x-admin.status-badge>
                @else
                    <x-admin.status-badge>
                        Inactive
                    </x-admin.status-badge>
                @endif
            </td>

            <td class="px-6 py-5">
                <span class="inline-flex rounded-full {{ $isUsed ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600' }} px-3 py-1 text-xs font-bold">
                    {{ $template->certificates_count }} certificates
                </span>

                @if ($isUsed)
                    <p class="mt-2 text-xs font-semibold text-slate-400">
                        Delete disabled
                    </p>
                @else
                    <p class="mt-2 text-xs font-semibold text-slate-400">
                        Safe to delete
                    </p>
                @endif
            </td>

            <td class="px-6 py-5">
                <div class="flex justify-center gap-2">
                    <button
                        type="button"
                        title="Edit"
                        @click='openEditModal(@json($editPayload))'
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-700 transition hover:bg-slate-200">
                        <x-admin.icon name="pencil" class="h-4 w-4" />
                    </button>

                    @if (! $isUsed)
                        <button
                            type="button"
                            title="Delete"
                            @click='openDeleteModal(@json($deletePayload))'
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100">
                            <x-admin.icon name="trash" class="h-4 w-4" />
                        </button>
                    @else
                        <button
                            type="button"
                            title="Template is already used by certificates"
                            disabled
                            class="inline-flex h-10 w-10 cursor-not-allowed items-center justify-center rounded-xl bg-slate-100 text-slate-300">
                            <x-admin.icon name="trash" class="h-4 w-4" />
                        </button>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="7"
            title="No certificate templates yet"
            description="Create your first template for certificate layout management." />
    @endforelse
</x-admin.data-table>