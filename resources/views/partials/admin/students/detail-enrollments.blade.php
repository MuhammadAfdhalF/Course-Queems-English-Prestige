<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-900">
                    Enrollment History
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Courses this student has access to.
                </p>
            </div>

            <a
                href="{{ route('admin.course-access.index') }}"
                class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-black text-slate-600 transition hover:bg-slate-50">
                View Course Access
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead class="border-b border-slate-200 bg-slate-50">
                <tr class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    <th class="px-6 py-4">Course</th>
                    <th class="px-6 py-4">Progress</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Enrolled</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($enrollments as $enrollment)
                <tr class="transition hover:bg-slate-50/70">
                    <td class="min-w-[220px] px-6 py-5">
                        <p class="font-bold text-slate-900">
                            {{ $enrollment->courseLevel?->name ?? 'Unknown Course' }}
                        </p>
                        <p class="mt-1 text-xs text-slate-400">
                            {{ $enrollment->courseLevel?->courseProgram?->name ?? '-' }}
                        </p>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        <p class="font-black text-slate-900">
                            {{ number_format((float) $enrollment->progress_percentage, 0) }}%
                        </p>

                        <div class="mt-2 h-2 w-28 overflow-hidden rounded-full bg-slate-100">
                            <div
                                class="h-full rounded-full bg-[var(--color-brand-blue)]"
                                style="width: {{ min(100, max(0, (float) $enrollment->progress_percentage)) }}%">
                            </div>
                        </div>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        @php
                        $variant = match ($enrollment->status) {
                        'completed' => 'completed',
                        'active' => 'approved',
                        'cancelled', 'expired' => 'rejected',
                        default => 'pending',
                        };
                        @endphp

                        <x-admin.status-badge :variant="$variant">
                            {{ ucfirst($enrollment->status) }}
                        </x-admin.status-badge>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-500">
                        {{ $enrollment->enrolled_at?->format('d M Y') ?? '-' }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 text-right">
                        <a
                            href="{{ route('admin.course-access.show', $enrollment) }}"
                            class="inline-flex h-9 items-center justify-center rounded-lg bg-[var(--color-brand-blue)] px-4 text-xs font-black text-white transition hover:opacity-90">
                            View Access
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <p class="text-sm font-black text-slate-700">No enrollments yet</p>
                        <p class="mt-1 text-sm text-slate-500">
                            Course access records will appear after an order is approved or access is created manually.
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin.table-card>