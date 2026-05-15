<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Student</th>
        <th class="px-6 py-4">Course</th>
        <th class="px-6 py-4">Certificate Number</th>
        <th class="px-6 py-4">Score</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4">Issued Date</th>
        <th class="px-6 py-4">Template</th>
        <th class="w-[180px] px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($certificates as $certificate)
        @php
            $statusClasses = match ($certificate->status) {
                'issued' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100',
                'revoked' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-100',
                'locked' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-100',
                default => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
            };

            $statusLabel = match ($certificate->status) {
                'issued' => 'Issued',
                'revoked' => 'Revoked',
                'locked' => 'Locked',
                default => ucfirst(str_replace('_', ' ', $certificate->status)),
            };

            $templateName = $certificate->certificateTemplate?->name ?? 'Default Template';
        @endphp

        <tr class="text-sm text-slate-700 transition hover:bg-slate-50/80">
            <td class="min-w-[220px] px-6 py-5">
                <p class="font-extrabold text-slate-900">
                    {{ $certificate->student?->name ?? 'Unknown Student' }}
                </p>

                <p class="mt-1 text-xs font-semibold text-slate-400">
                    {{ $certificate->student?->email ?? '-' }}
                </p>
            </td>

            <td class="min-w-[230px] px-6 py-5">
                <p class="font-bold text-slate-900">
                    {{ $certificate->courseLevel?->name ?? 'Unknown Course' }}
                </p>

                <p class="mt-1 text-xs font-semibold text-slate-400">
                    {{ $certificate->courseLevel?->courseProgram?->name ?? 'Course Program' }}
                </p>
            </td>

            <td class="min-w-[200px] px-6 py-5">
                <p class="break-all font-extrabold text-slate-900">
                    {{ $certificate->certificate_number }}
                </p>

                <p class="mt-1 text-xs font-semibold text-slate-400">
                    ID: {{ $certificate->id }}
                </p>
            </td>

            <td class="px-6 py-5">
                <p class="font-extrabold text-slate-900">
                    {{ $certificate->finalExamAttempt ? number_format((float) $certificate->finalExamAttempt->total_score, 2) . '%' : '-' }}
                </p>

                <p class="mt-1 text-xs font-semibold text-slate-400">
                    Internal
                </p>
            </td>

            <td class="px-6 py-5">
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-extrabold uppercase tracking-[0.12em] {{ $statusClasses }}">
                    {{ $statusLabel }}
                </span>
            </td>

            <td class="px-6 py-5">
                <p class="text-sm font-bold text-slate-700">
                    {{ $certificate->issued_at?->format('d M Y') ?? '-' }}
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    {{ $certificate->issued_at?->format('H:i') ?? '' }}
                </p>
            </td>

            <td class="min-w-[180px] px-6 py-5">
                <p class="font-bold text-slate-900">
                    {{ $templateName }}
                </p>

                <p class="mt-1 text-xs font-semibold text-slate-400">
                    {{ $certificate->certificateTemplate?->courseProgram?->name ?? 'Global / Program Template' }}
                </p>
            </td>

            <td class="px-6 py-5">
                <div class="flex min-w-[180px] flex-wrap justify-center gap-2">
                    <a
                        href="{{ route('admin.course-management.certificates.show', $certificate) }}"
                        class="inline-flex h-9 items-center justify-center gap-2 rounded-lg bg-[var(--color-brand-blue)] px-4 text-xs font-bold text-white transition hover:opacity-90">
                        <x-admin.icon name="eye" class="h-4 w-4" />
                        <span>View</span>
                    </a>

                    @if ($certificate->status === 'issued')
                        <a
                            href="{{ route('admin.course-management.certificates.download', $certificate) }}"
                            class="inline-flex h-9 items-center justify-center rounded-lg bg-emerald-50 px-4 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100">
                            Download
                        </a>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="8"
            title="No certificates yet"
            description="Student certificates will appear here after students pass final exams and complete the certificate unlock flow." />
    @endforelse
</x-admin.data-table>