@php
$statusVariant = match ($enrollment->status) {
'active' => 'approved',
'completed' => 'completed',
'cancelled', 'expired' => 'rejected',
default => 'pending',
};

$sourceVariant = $enrollment->enrollment_source === 'manual' ? 'pending' : 'approved';

$progress = (float) $enrollment->progress_percentage;
@endphp

<x-admin.table-card class="overflow-hidden">
    <div class="grid gap-0 xl:grid-cols-[0.85fr_1.15fr]">
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-[var(--color-brand-blue)] to-slate-800 p-7 text-white">
            <div class="pointer-events-none absolute -right-16 -top-16 h-44 w-44 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-16 h-44 w-44 rounded-full bg-yellow-400/20 blur-3xl"></div>

            <div class="relative">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-white/50">
                    Student
                </p>

                <h1 class="mt-3 text-3xl font-black leading-tight">
                    {{ $student->name }}
                </h1>

                <p class="mt-2 break-all text-sm font-semibold text-white/70">
                    {{ $student->email }}
                </p>

                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="inline-flex rounded-full bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[0.14em] text-white">
                        {{ $student->is_active ? 'Active Account' : 'Inactive Account' }}
                    </span>

                    <span class="inline-flex rounded-full bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[0.14em] text-white">
                        {{ $profile?->whatsapp ?? 'No WhatsApp' }}
                    </span>
                </div>

                <div class="mt-7 rounded-[24px] border border-white/10 bg-white/10 p-5">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-white/50">
                        Overall Progress
                    </p>

                    <div class="mt-4 flex items-end gap-2">
                        <span class="text-5xl font-black leading-none">{{ number_format($progress, 0) }}%</span>
                        <span class="pb-1 text-sm font-bold text-white/60">completed</span>
                    </div>

                    <div class="mt-4 h-3 overflow-hidden rounded-full bg-white/20">
                        <div
                            class="h-full rounded-full bg-white"
                            style="width: {{ min(100, max(0, $progress)) }}%;">
                        </div>
                    </div>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <a
                        href="{{ route('admin.students.show', $student) }}"
                        class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-center text-sm font-black text-white transition hover:bg-white/15">
                        View Student
                    </a>

                    @if ($enrollment->order)
                    <a
                        href="{{ route('admin.orders.show', $enrollment->order) }}"
                        class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-center text-sm font-black text-white transition hover:bg-white/15">
                        View Order
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-7">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                        Course Access
                    </p>

                    <h2 class="mt-2 text-3xl font-black text-slate-900">
                        {{ $courseLevel->name }}
                    </h2>

                    <p class="mt-2 text-sm font-semibold text-slate-500">
                        {{ $courseProgram?->name ?? '-' }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <x-admin.status-badge :variant="$statusVariant">
                        {{ ucfirst($enrollment->status) }}
                    </x-admin.status-badge>

                    <x-admin.status-badge :variant="$sourceVariant">
                        {{ ucfirst($enrollment->enrollment_source ?? 'unknown') }}
                    </x-admin.status-badge>
                </div>
            </div>

            <div class="mt-7 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">Enrolled At</p>
                    <p class="mt-2 text-sm font-black text-slate-900">
                        {{ $enrollment->enrolled_at?->format('d F Y H:i') ?? '-' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">Completed At</p>
                    <p class="mt-2 text-sm font-black text-slate-900">
                        {{ $enrollment->completed_at?->format('d F Y H:i') ?? '-' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">Expired At</p>
                    <p class="mt-2 text-sm font-black text-slate-900">
                        {{ $enrollment->expired_at?->format('d F Y H:i') ?? '-' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">Created By</p>
                    <p class="mt-2 text-sm font-black text-slate-900">
                        {{ $enrollment->createdBy?->name ?? '-' }}
                    </p>
                </div>

                @if ($enrollment->order)
                <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 sm:col-span-2">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-blue-500">Order Source</p>

                    <a
                        href="{{ route('admin.orders.show', $enrollment->order) }}"
                        class="mt-2 inline-flex break-all text-sm font-black text-[var(--color-brand-blue)] hover:underline">
                        {{ $enrollment->order->order_code }} · Rp {{ number_format((float) $enrollment->order->price, 0, ',', '.') }}
                    </a>
                </div>
                @endif

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 sm:col-span-2">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">Note</p>
                    <p class="mt-2 whitespace-pre-line text-sm font-semibold leading-6 text-slate-700">
                        {{ $enrollment->note ?: '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-admin.table-card>