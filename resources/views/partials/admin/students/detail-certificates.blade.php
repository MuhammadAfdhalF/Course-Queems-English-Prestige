<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-xl font-black text-slate-900">
            Certificate History
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Certificates generated for this student.
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead class="border-b border-slate-200 bg-slate-50">
                <tr class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    <th class="px-6 py-4">Certificate</th>
                    <th class="px-6 py-4">Course</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Issued</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($certificates as $certificate)
                <tr>
                    <td class="min-w-[180px] px-6 py-5">
                        <p class="break-all font-bold text-slate-900">
                            {{ $certificate->certificate_number }}
                        </p>
                    </td>

                    <td class="min-w-[220px] px-6 py-5">
                        <p class="font-bold text-slate-900">
                            {{ $certificate->courseLevel?->name ?? 'Unknown Course' }}
                        </p>
                        <p class="mt-1 text-xs text-slate-400">
                            {{ $certificate->courseLevel?->courseProgram?->name ?? '-' }}
                        </p>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        @php
                        $variant = match ($certificate->status) {
                        'issued' => 'completed',
                        'revoked' => 'rejected',
                        default => 'pending',
                        };
                        @endphp

                        <x-admin.status-badge :variant="$variant">
                            {{ ucfirst($certificate->status) }}
                        </x-admin.status-badge>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-500">
                        {{ $certificate->issued_at?->format('d M Y') ?? '-' }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        <a
                            href="{{ route('admin.course-management.certificates.show', $certificate) }}"
                            class="inline-flex h-9 items-center justify-center rounded-lg bg-[var(--color-brand-blue)] px-4 text-xs font-bold text-white transition hover:opacity-90">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                        No certificates yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin.table-card>