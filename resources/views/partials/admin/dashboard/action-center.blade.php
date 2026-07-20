<x-admin.table-card class="overflow-hidden">
    <div class="border-b border-slate-200 bg-slate-50/70 px-6 py-5">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                    Priority
                </p>

                <h3 class="mt-1 text-2xl font-black text-slate-900">
                    Action Center
                </h3>

                <p class="mt-1 text-sm font-semibold text-slate-500">
                    Items that may need admin attention.
                </p>
            </div>

            @php
            $totalActionCount = collect($actionItems)->sum('count');
            @endphp

            <span class="inline-flex rounded-full {{ $totalActionCount > 0 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700' }} px-4 py-2 text-xs font-black">
                {{ $totalActionCount > 0 ? $totalActionCount . ' Need Action' : 'All Clear' }}
            </span>
        </div>
    </div>

    <div class="space-y-4 p-5">
        @foreach ($actionItems as $item)
        @php
        $hasAction = (int) $item['count'] > 0;

        $toneClasses = match ($item['tone']) {
        'amber' => $hasAction
        ? 'bg-amber-50 text-amber-700 border-amber-100'
        : 'bg-slate-50 text-slate-500 border-slate-200',
        'emerald' => $hasAction
        ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
        : 'bg-slate-50 text-slate-500 border-slate-200',
        default => $hasAction
        ? 'bg-blue-50 text-blue-700 border-blue-100'
        : 'bg-slate-50 text-slate-500 border-slate-200',
        };

        $buttonClasses = $hasAction
        ? 'bg-[var(--color-brand-blue)] text-white hover:opacity-90'
        : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50';
        @endphp

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-base font-black text-slate-900">
                            {{ $item['title'] }}
                        </p>

                        @if (! $hasAction)
                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.1em] text-emerald-700">
                            Clear
                        </span>
                        @endif
                    </div>

                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                        {{ $item['description'] }}
                    </p>
                </div>

                <span class="inline-flex min-w-[46px] justify-center rounded-2xl border px-3 py-2 text-sm font-black {{ $toneClasses }}">
                    {{ $item['count'] }}
                </span>
            </div>

            <a
                href="{{ $item['href'] }}"
                class="mt-5 inline-flex h-10 items-center justify-center rounded-xl px-4 text-xs font-black transition {{ $buttonClasses }}">
                {{ $item['buttonLabel'] }}
            </a>
        </div>
        @endforeach
    </div>
</x-admin.table-card>