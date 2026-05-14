@props([
'items' => [],
])

@php
$earnedCount = collect($items)
->where('issued', true)
->count();
@endphp

<div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between gap-4">
        <h3 class="text-[18px] font-bold text-slate-900">My Certificates</h3>

        <span class="inline-flex items-center rounded-lg bg-yellow-50 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.08em] text-[var(--color-brand-gold)]">
            {{ $earnedCount }} Earned
        </span>
    </div>

    <div class="mt-6 space-y-4">
        @forelse ($items as $item)
        <div class="rounded-2xl border {{ !empty($item['locked']) ? 'border-slate-200 bg-slate-50 opacity-80' : 'border-slate-200 bg-white' }} p-4 transition hover:shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ !empty($item['locked']) ? 'bg-slate-100 text-slate-400' : 'bg-yellow-50 text-[var(--color-brand-gold)]' }}">
                    @if (!empty($item['locked']))
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="6" y="10" width="12" height="10" rx="2" stroke-width="1.8" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10V8a4 4 0 118 0v2" />
                    </svg>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                    </svg>
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    <h4 class="truncate text-[15px] font-bold text-slate-900">
                        {{ $item['title'] }}
                    </h4>

                    <p class="mt-1 text-[13px] font-medium text-slate-400">
                        ID: {{ $item['id'] }}
                    </p>

                    @if (!empty($item['locked']))
                    <a
                        href="{{ $item['href'] ?? '#' }}"
                        class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-brand-gold)] hover:opacity-80">
                        {{ $item['note'] ?? 'Write testimonial to unlock' }}
                    </a>
                    @elseif (($item['status'] ?? null) === 'revoked')
                    <p class="mt-3 text-sm font-semibold text-rose-600">
                        {{ $item['note'] ?? 'Certificate revoked' }}
                    </p>
                    @else
                    <p class="mt-3 text-sm font-semibold text-emerald-600">
                        {{ $item['note'] ?? 'Certificate issued' }}
                    </p>

                    <a
                        href="{{ $item['href'] ?? '#' }}"
                        class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-brand-blue)] hover:opacity-80">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Certificate
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center">
            <h4 class="text-base font-extrabold text-slate-900">
                No Certificates Yet
            </h4>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Certificates will appear after you pass a final exam.
            </p>
        </div>
        @endforelse
    </div>

    <a
        href="{{ route('student.testimoni') }}"
        class="mt-6 inline-flex h-12 w-full items-center justify-center rounded-xl border border-slate-300 bg-white text-base font-bold text-slate-700 transition hover:bg-slate-50">
        Unlock Certificates
    </a>
</div>