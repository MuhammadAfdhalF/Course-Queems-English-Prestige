@props([
'items' => [],
])

<div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between gap-4">
        <h3 class="text-[18px] font-bold text-slate-900">My Certificates</h3>

        <span class="inline-flex items-center rounded-lg bg-yellow-50 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.08em] text-[var(--color-brand-gold)]">
            2 Earned
        </span>
    </div>

    <div class="mt-6 space-y-4">
        @foreach ($items as $item)
        <div class="rounded-2xl border {{ !empty($item['locked']) ? 'border-slate-200 bg-slate-50 opacity-70' : 'border-slate-200 bg-white' }} p-4 transition hover:shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ !empty($item['locked']) ? 'bg-slate-100 text-slate-400' : 'bg-yellow-50 text-[var(--color-brand-gold)]' }}">
                    @if(!empty($item['locked']))
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

                    @if(empty($item['locked']))
                    <a href="#" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-brand-blue)] hover:opacity-80">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v12m0 0l4-4m-4 4l-4-4M5 19h14" />
                        </svg>
                        Download PDF
                    </a>
                    @elseif(!empty($item['note']))
                    <p class="mt-3 text-sm font-semibold text-[var(--color-brand-gold)]">
                        {{ $item['note'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <button
        type="button"
        class="mt-6 inline-flex h-12 w-full items-center justify-center rounded-xl border border-slate-300 bg-white text-base font-bold text-slate-700 transition hover:bg-slate-50">
        View All Achievements
    </button>
</div>