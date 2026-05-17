<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-xl font-black text-slate-900">
            Enrollment History
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Courses this student has access to.
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead class="border-b border-slate-200 bg-slate-50">
                <tr class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    <th class="px-6 py-4">Course</th>
                    <th class="px-6 py-4">Progress</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Enrolled</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($enrollments as $enrollment)
                <tr>
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
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">
                        No enrollments yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin.table-card>