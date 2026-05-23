<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Student</th>
        <th class="px-6 py-4">Course</th>
        <th class="px-6 py-4">Progress</th>
        <th class="px-6 py-4">Source</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4">Enrolled</th>
        <th class="px-6 py-4">End Date</th>
        <th class="w-[180px] px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($enrollments as $enrollment)
    @php
    $statusVariant = match ($enrollment->status) {
    'active' => 'approved',
    'completed' => 'completed',
    'cancelled', 'expired' => 'rejected',
    default => 'pending',
    };

    $sourceVariant = $enrollment->enrollment_source === 'manual' ? 'pending' : 'approved';

    $endDate = $enrollment->completed_at
    ? $enrollment->completed_at->format('d M Y')
    : ($enrollment->expired_at?->format('d M Y') ?? '-');

    $progress = (float) $enrollment->progress_percentage;
    $progressColor = $progress >= 100
    ? 'bg-emerald-500'
    : ($progress > 0 ? 'bg-[var(--color-brand-blue)]' : 'bg-slate-300');
    @endphp

    <tr class="text-sm text-slate-700 transition hover:bg-slate-50/80">
        <td class="min-w-[260px] px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-sm font-black text-[var(--color-brand-blue)]">
                    {{ strtoupper(substr($enrollment->student?->name ?? 'S', 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <a
                        href="{{ $enrollment->student ? route('admin.students.show', $enrollment->student) : '#' }}"
                        class="truncate font-black text-slate-900 hover:text-[var(--color-brand-blue)] hover:underline">
                        {{ $enrollment->student?->name ?? 'Unknown Student' }}
                    </a>

                    <p class="mt-1 truncate text-xs font-semibold text-slate-400">
                        {{ $enrollment->student?->email ?? '-' }}
                    </p>
                </div>
            </div>
        </td>

        <td class="min-w-[250px] px-6 py-5">
            <p class="font-black text-slate-900">
                {{ $enrollment->courseLevel?->name ?? 'Unknown Course' }}
            </p>

            <p class="mt-1 text-xs font-semibold text-slate-400">
                {{ $enrollment->courseLevel?->courseProgram?->name ?? '-' }}
            </p>

            @if ($enrollment->order)
            <a
                href="{{ route('admin.orders.show', $enrollment->order) }}"
                class="mt-2 inline-flex text-xs font-black text-[var(--color-brand-blue)] hover:underline">
                {{ $enrollment->order->order_code }}
            </a>
            @endif
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            <div class="min-w-[130px]">
                <div class="flex items-center justify-between gap-3">
                    <p class="font-black text-slate-900">
                        {{ number_format($progress, 0) }}%
                    </p>

                    @if ($progress >= 100)
                    <span class="text-xs font-black text-emerald-600">Done</span>
                    @elseif ($progress > 0)
                    <span class="text-xs font-black text-blue-600">Learning</span>
                    @else
                    <span class="text-xs font-black text-slate-400">Start</span>
                    @endif
                </div>

                <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-slate-100">
                    <div
                        class="h-full rounded-full {{ $progressColor }}"
                        style="width: {{ min(100, max(0, $progress)) }}%;">
                    </div>
                </div>
            </div>
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            <x-admin.status-badge :variant="$sourceVariant">
                {{ ucfirst($enrollment->enrollment_source ?? 'unknown') }}
            </x-admin.status-badge>
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            <x-admin.status-badge :variant="$statusVariant">
                {{ ucfirst($enrollment->status) }}
            </x-admin.status-badge>
        </td>

        <td class="whitespace-nowrap px-6 py-5 text-sm font-semibold text-slate-600">
            {{ $enrollment->enrolled_at?->format('d M Y') ?? '-' }}
        </td>

        <td class="whitespace-nowrap px-6 py-5 text-sm font-semibold text-slate-600">
            {{ $endDate }}
        </td>

        <td class="px-6 py-5">
            <div class="flex justify-center">
                <a
                    href="{{ route('admin.course-access.show', $enrollment) }}"
                    class="inline-flex h-10 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-xs font-black text-white transition hover:opacity-90">
                    View Detail
                </a>
            </div>
        </td>
    </tr>
    @empty
    <x-admin.empty-state
        colspan="8"
        title="No course access found"
        description="Course access records will appear after orders are approved or admin grants access manually." />
    @endforelse

    <x-slot:footer>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm font-semibold text-slate-500">
                Showing {{ $enrollments->firstItem() ?? 0 }} - {{ $enrollments->lastItem() ?? 0 }} of {{ $enrollments->total() }} records
            </p>

            <div>
                {{ $enrollments->links() }}
            </div>
        </div>
    </x-slot:footer>
</x-admin.data-table>