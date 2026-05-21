<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-xl font-black text-slate-900">
            Module Progress
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Student progress for each module in this course.
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead class="border-b border-slate-200 bg-slate-50">
                <tr class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    <th class="px-6 py-4">Module</th>
                    <th class="px-6 py-4">Progress</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Started</th>
                    <th class="px-6 py-4">Completed</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($modules as $item)
                @php
                $variant = match ($item['status']) {
                'completed' => 'completed',
                'in_progress' => 'approved',
                default => 'pending',
                };
                @endphp

                <tr>
                    <td class="min-w-[240px] px-6 py-5">
                        <p class="font-bold text-slate-900">
                            {{ $item['module']->title }}
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            {{ $item['module']->materials->count() }} materials · {{ $item['module']->practices->count() }} practices
                        </p>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        <p class="font-black text-slate-900">
                            {{ number_format((float) $item['progressPercentage'], 0) }}%
                        </p>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        <x-admin.status-badge :variant="$variant">
                            {{ str_replace('_', ' ', ucfirst($item['status'])) }}
                        </x-admin.status-badge>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-500">
                        {{ $item['startedAt']?->format('d M Y H:i') ?? '-' }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-500">
                        {{ $item['completedAt']?->format('d M Y H:i') ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                        No modules found for this course.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin.table-card>