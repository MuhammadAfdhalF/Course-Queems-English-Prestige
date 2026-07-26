<div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-2xs">
    <div class="flex items-center justify-between border-b border-slate-100 bg-white px-5 py-4 sm:px-6">
        <div>
            <h3 class="text-base font-bold text-slate-900">
                Action Center
            </h3>
            <p class="text-xs font-medium text-slate-500">
                Items requiring admin attention.
            </p>
        </div>

        @php
        $totalActionCount = collect($actionItems)->sum('count');
        @endphp

        <span class="inline-flex rounded-full {{ $totalActionCount > 0 ? 'bg-[#AD6B10]/10 text-[#AD6B10]' : 'bg-emerald-50 text-emerald-700' }} px-3 py-1 text-xs font-extrabold">
            {{ $totalActionCount > 0 ? $totalActionCount . ' Pending' : 'All Clear' }}
        </span>
    </div>

    <div class="divide-y divide-slate-100">
        @foreach ($actionItems as $item)
        @php
        $hasAction = (int) $item['count'] > 0;
        @endphp

        <a
            href="{{ $item['href'] }}"
            class="group flex items-center justify-between gap-4 p-4 transition-colors hover:bg-slate-50/80">
            <div class="flex items-center gap-3 min-w-0">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $hasAction ? 'bg-[#AD6B10]/10 text-[#AD6B10]' : 'bg-slate-100 text-slate-400' }}">
                    @if($hasAction)
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    @endif
                </div>

                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-900 group-hover:text-[#080D4D] truncate">
                        {{ $item['title'] }}
                    </p>
                    <p class="text-[11px] font-medium text-slate-500 truncate">
                        {{ $item['description'] }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex min-w-[28px] justify-center rounded-md border px-2 py-0.5 text-xs font-bold {{ $hasAction ? 'border-amber-200 bg-[#AD6B10]/10 text-[#AD6B10]' : 'border-slate-200/80 bg-slate-50 text-slate-400' }}">
                    {{ $item['count'] }}
                </span>

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 group-hover:text-[#080D4D] transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>
        @endforeach
    </div>
</div>