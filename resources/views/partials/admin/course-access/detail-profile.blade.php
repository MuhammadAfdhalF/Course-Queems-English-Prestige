@php
$statusVariant = match ($enrollment->status) {
'active' => 'approved',
'completed' => 'completed',
'cancelled', 'expired' => 'rejected',
default => 'pending',
};

$sourceVariant = $enrollment->enrollment_source === 'manual' ? 'pending' : 'approved';
@endphp

<x-admin.table-card class="p-6">
    <div class="grid gap-6 xl:grid-cols-[1fr_1fr]">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                Student
            </p>

            <h1 class="mt-2 text-3xl font-black text-slate-900">
                {{ $student->name }}
            </h1>

            <p class="mt-2 break-all text-sm font-semibold text-slate-500">
                {{ $student->email }}
            </p>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">WhatsApp</p>
                    <p class="mt-2 text-sm font-bold text-slate-900">{{ $profile?->whatsapp ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Account Status</p>
                    <p class="mt-2 text-sm font-bold text-slate-900">
                        {{ $student->is_active ? 'Active' : 'Inactive' }}
                    </p>
                </div>
            </div>
        </div>

        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                Course Access
            </p>

            <h2 class="mt-2 text-3xl font-black text-slate-900">
                {{ $courseLevel->name }}
            </h2>

            <p class="mt-2 text-sm font-semibold text-slate-500">
                {{ $courseProgram?->name ?? '-' }}
            </p>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Status</p>
                    <div class="mt-2">
                        <x-admin.status-badge :variant="$statusVariant">
                            {{ ucfirst($enrollment->status) }}
                        </x-admin.status-badge>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Source</p>
                    <div class="mt-2">
                        <x-admin.status-badge :variant="$sourceVariant">
                            {{ ucfirst($enrollment->enrollment_source ?? 'unknown') }}
                        </x-admin.status-badge>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Progress</p>
                    <p class="mt-2 text-sm font-black text-slate-900">
                        {{ number_format((float) $enrollment->progress_percentage, 2) }}%
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Enrolled At</p>
                    <p class="mt-2 text-sm font-black text-slate-900">
                        {{ $enrollment->enrolled_at?->format('d F Y H:i') ?? '-' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Completed At</p>
                    <p class="mt-2 text-sm font-black text-slate-900">
                        {{ $enrollment->completed_at?->format('d F Y H:i') ?? '-' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Expired At</p>
                    <p class="mt-2 text-sm font-black text-slate-900">
                        {{ $enrollment->expired_at?->format('d F Y H:i') ?? '-' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 sm:col-span-2">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Created By</p>
                    <p class="mt-2 text-sm font-black text-slate-900">
                        {{ $enrollment->createdBy?->name ?? '-' }}
                    </p>
                </div>

                @if ($enrollment->order)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 sm:col-span-2">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Order Source</p>
                    <p class="mt-2 text-sm font-black text-slate-900">
                        {{ $enrollment->order->order_code }} · Rp {{ number_format((float) $enrollment->order->price, 0, ',', '.') }}
                    </p>
                </div>
                @endif

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 sm:col-span-2">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Note</p>
                    <p class="mt-2 whitespace-pre-line text-sm font-semibold leading-6 text-slate-700">
                        {{ $enrollment->note ?: '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-admin.table-card>