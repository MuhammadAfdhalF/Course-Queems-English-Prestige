<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Student</th>
        <th class="px-6 py-4">WhatsApp</th>
        <th class="px-6 py-4">Joined</th>
        <th class="px-6 py-4">Courses</th>
        <th class="px-6 py-4">Orders</th>
        <th class="px-6 py-4">Certificates</th>
        <th class="px-6 py-4">Status</th>
        <th class="w-[160px] px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($students as $student)
    @php
    $profile = $student->studentProfile;
    $initials = collect(explode(' ', trim($student->name)))
    ->filter()
    ->take(2)
    ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
    ->implode('');

    $initials = $initials ?: 'S';

    $avatarUrl = $profile?->photo
    ? asset('storage/' . $profile->photo)
    : null;
    @endphp

    <tr class="text-sm text-slate-700 transition hover:bg-slate-50/80">
        <td class="min-w-[260px] px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-50 text-sm font-black text-[var(--color-brand-blue)]">
                    @if ($avatarUrl)
                    <img
                        src="{{ $avatarUrl }}"
                        alt="{{ $student->name }}"
                        class="h-full w-full object-cover">
                    @else
                    {{ $initials }}
                    @endif
                </div>

                <div class="min-w-0">
                    <p class="truncate font-extrabold text-slate-900">
                        {{ $student->name }}
                    </p>

                    <p class="mt-1 truncate text-xs font-semibold text-slate-400">
                        {{ $student->email }}
                    </p>
                </div>
            </div>
        </td>

        <td class="min-w-[170px] px-6 py-5">
            <p class="font-semibold text-slate-700">
                {{ $profile?->whatsapp ?? '-' }}
            </p>
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            <p class="font-semibold text-slate-700">
                {{ $student->created_at?->format('d M Y') ?? '-' }}
            </p>
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            <p class="font-extrabold text-slate-900">
                {{ $student->active_enrollments_count }} Active
            </p>

            <p class="mt-1 text-xs font-semibold text-slate-400">
                {{ $student->completed_enrollments_count }} Completed
            </p>
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            <p class="font-extrabold text-slate-900">
                {{ $student->orders_count }}
            </p>
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            <p class="font-extrabold text-slate-900">
                {{ $student->certificates_count }}
            </p>
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            @if ($student->is_active)
            <x-admin.status-badge variant="completed">
                Active
            </x-admin.status-badge>
            @else
            <x-admin.status-badge variant="rejected">
                Inactive
            </x-admin.status-badge>
            @endif
        </td>

        <td class="px-6 py-5">
            <div class="flex justify-center">
                <a
                    href="{{ route('admin.students.show', $student) }}"
                    class="inline-flex h-10 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-xs font-bold text-white transition hover:opacity-90">
                    View Detail
                </a>
            </div>
        </td>
    </tr>
    @empty
    <x-admin.empty-state
        colspan="8"
        title="No students found"
        description="Student accounts will appear here after users register as students." />
    @endforelse

    <x-slot:footer>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm font-semibold text-slate-500">
                Showing {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students
            </p>

            <div>
                {{ $students->links() }}
            </div>
        </div>
    </x-slot:footer>
</x-admin.data-table>