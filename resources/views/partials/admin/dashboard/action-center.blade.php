<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <h3 class="text-2xl font-bold text-slate-900">
            Action Center
        </h3>

        <p class="mt-1 text-sm text-slate-500">
            Items that may need admin attention.
        </p>
    </div>

    <div class="space-y-4 p-6">
        @foreach ($actionItems as $item)
        @php
        $toneClasses = match ($item['tone']) {
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
        default => 'bg-blue-50 text-blue-700 ring-blue-100',
        };
        @endphp

        <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-extrabold text-slate-900">
                        {{ $item['title'] }}
                    </p>

                    <p class="mt-1 text-xs leading-5 text-slate-500">
                        {{ $item['description'] }}
                    </p>
                </div>

                <span class="inline-flex min-w-[42px] justify-center rounded-full px-3 py-1 text-xs font-black ring-1 {{ $toneClasses }}">
                    {{ $item['count'] }}
                </span>
            </div>

            <a
                href="{{ $item['href'] }}"
                class="mt-4 inline-flex h-9 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                {{ $item['buttonLabel'] }}
            </a>
        </div>
        @endforeach
    </div>
</x-admin.table-card>