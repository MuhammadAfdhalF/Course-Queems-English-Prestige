<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-xl font-black text-slate-900">
            Certificate
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Certificate connected to this enrollment.
        </p>
    </div>

    <div class="p-6">
        @if ($certificate)
        @php
        $variant = match ($certificate->status) {
        'issued' => 'completed',
        'revoked' => 'rejected',
        default => 'pending',
        };
        @endphp

        <div class="space-y-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    Certificate Number
                </p>

                <p class="mt-2 break-all text-lg font-black text-slate-900">
                    {{ $certificate->certificate_number }}
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    Status
                </p>

                <div class="mt-2">
                    <x-admin.status-badge :variant="$variant">
                        {{ ucfirst($certificate->status) }}
                    </x-admin.status-badge>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    Issued At
                </p>

                <p class="mt-2 text-sm font-bold text-slate-900">
                    {{ $certificate->issued_at?->format('d F Y H:i') ?? '-' }}
                </p>
            </div>

            <a
                href="{{ route('admin.course-management.certificates.show', $certificate) }}"
                class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white transition hover:opacity-90">
                View Certificate
            </a>
        </div>
        @else
        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center">
            <h3 class="text-lg font-black text-slate-900">
                No certificate yet
            </h3>

            <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">
                Certificate will appear after the student completes final exam and certificate flow.
            </p>
        </div>
        @endif
    </div>
</x-admin.table-card>