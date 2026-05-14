<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Student</th>
        <th class="px-6 py-4">Course</th>
        <th class="px-6 py-4">Certificate Number</th>
        <th class="px-6 py-4">Score</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4">Issued Date</th>
        <th class="px-6 py-4">PDF</th>
        <th class="w-[220px] px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($certificates as $certificate)
    @php
    $statusClasses = match ($certificate->status) {
    'issued' => 'bg-emerald-50 text-emerald-700',
    'revoked' => 'bg-rose-50 text-rose-700',
    'locked' => 'bg-amber-50 text-amber-700',
    default => 'bg-slate-100 text-slate-600',
    };

    $statusLabel = match ($certificate->status) {
    'issued' => 'Issued',
    'revoked' => 'Revoked',
    'locked' => 'Locked',
    default => ucfirst(str_replace('_', ' ', $certificate->status)),
    };

    $pdfAvailable = $certificate->certificate_file
    && Storage::disk('public')->exists($certificate->certificate_file);
    @endphp

    <tr class="text-sm text-slate-700 transition hover:bg-slate-50/80">
        <td class="min-w-[210px] px-6 py-5">
            <p class="font-extrabold text-slate-900">
                {{ $certificate->student?->name ?? 'Unknown Student' }}
            </p>

            <p class="mt-1 text-xs font-semibold text-slate-400">
                {{ $certificate->student?->email ?? '-' }}
            </p>
        </td>

        <td class="min-w-[220px] px-6 py-5">
            <p class="font-semibold text-slate-900">
                {{ $certificate->courseLevel?->name ?? 'Unknown Course' }}
            </p>

            <p class="mt-1 text-xs font-semibold text-slate-400">
                {{ $certificate->courseLevel?->courseProgram?->name ?? 'Course Program' }}
            </p>
        </td>

        <td class="min-w-[190px] px-6 py-5">
            <p class="break-all font-bold text-slate-900">
                {{ $certificate->certificate_number }}
            </p>
        </td>

        <td class="px-6 py-5">
            <p class="font-extrabold text-slate-900">
                {{ $certificate->finalExamAttempt ? number_format((float) $certificate->finalExamAttempt->total_score, 2) . '%' : '-' }}
            </p>
        </td>

        <td class="px-6 py-5">
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses }}">
                {{ $statusLabel }}
            </span>
        </td>

        <td class="px-6 py-5">
            <p class="text-sm font-semibold text-slate-700">
                {{ $certificate->issued_at?->format('d M Y') ?? '-' }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                {{ $certificate->issued_at?->format('H:i') ?? '' }}
            </p>
        </td>

        <td class="px-6 py-5">
            @if ($pdfAvailable)
            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                Generated
            </span>
            @else
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                Not Generated
            </span>
            @endif
        </td>

        <td class="px-6 py-5">
            <div class="flex min-w-[220px] flex-wrap justify-center gap-2">
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

                <form
                    action="{{ route('admin.course-management.certificates.revoke', $certificate) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to revoke this certificate?')">
                    @csrf
                    @method('PUT')

                    <button
                        type="submit"
                        class="inline-flex h-9 items-center justify-center rounded-lg bg-rose-50 px-4 text-xs font-bold text-rose-700 transition hover:bg-rose-100">
                        Revoke
                    </button>
                </form>
                @elseif ($certificate->status === 'revoked')
                <form
                    action="{{ route('admin.course-management.certificates.reissue', $certificate) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to re-issue this certificate?')">
                    @csrf
                    @method('PUT')

                    <button
                        type="submit"
                        class="inline-flex h-9 items-center justify-center rounded-lg bg-emerald-50 px-4 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100">
                        Re-issue
                    </button>
                </form>
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