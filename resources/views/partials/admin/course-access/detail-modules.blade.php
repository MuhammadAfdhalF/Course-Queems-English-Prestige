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

                $moduleProgress = (float) $item['progressPercentage'];
                @endphp

                <tr class="transition hover:bg-slate-50/70">
                    <td class="min-w-[240px] px-6 py-5">
                        <p class="font-black text-slate-900">
                            {{ $item['module']->title }}
                        </p>

                        <p class="mt-1 text-xs font-semibold text-slate-400">
                            {{ $item['module']->materials->count() }} materials · {{ $item['module']->practices->count() }} practices
                        </p>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        <div class="min-w-[120px]">
                            <p class="font-black text-slate-900">
                                {{ number_format($moduleProgress, 0) }}%
                            </p>

                            <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full {{ $moduleProgress >= 100 ? 'bg-emerald-500' : 'bg-[var(--color-brand-blue)]' }}"
                                    style="width: {{ min(100, max(0, $moduleProgress)) }}%;">
                                </div>
                            </div>
                        </div>
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
                    <td colspan="5" class="px-6 py-12 text-center">
                        <p class="text-sm font-black text-slate-700">No modules found</p>
                        <p class="mt-1 text-sm text-slate-500">Modules for this course will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin.table-card>