<div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-2xs">
    <div class="flex items-center justify-between border-b border-slate-100 bg-white px-5 py-4 sm:px-6">
        <div>
            <h3 class="text-base font-bold text-slate-900">
                Waiting Reviews
            </h3>
            <p class="text-xs font-medium text-slate-500">
                Attempts waiting for grading.
            </p>
        </div>

        <a href="{{ route('admin.course-management.programs.index') }}" class="text-xs font-bold text-[#080D4D] hover:underline">
            Open courses
        </a>
    </div>

    <div class="divide-y divide-slate-100">
        @forelse ($waitingReviewItems as $item)
        <div class="p-3.5 sm:p-4 transition hover:bg-slate-50/80">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex rounded-md {{ $item['type'] === 'Final Exam' ? 'bg-[#080D4D]/10 text-[#080D4D]' : 'bg-[#AD6B10]/10 text-[#AD6B10]' }} px-2 py-0.5 text-[10px] font-extrabold">
                            {{ $item['type'] }}
                        </span>
                        <span class="text-[11px] font-medium text-slate-400">
                            {{ $item['submittedAt'] }}
                        </span>
                    </div>

                    <h4 class="mt-1 text-xs font-bold text-slate-900 truncate">
                        {{ $item['assessment'] }}
                    </h4>

                    <p class="text-[11px] font-semibold text-slate-600 truncate">
                        {{ $item['student'] }} @if ($item['course']) <span class="font-normal text-slate-400">• {{ $item['course'] }}</span> @endif
                    </p>
                </div>

                <a
                    href="{{ $item['href'] }}"
                    class="inline-flex h-8 shrink-0 items-center justify-center rounded-lg bg-[#080D4D] px-3 text-xs font-bold text-white shadow-2xs transition hover:bg-[#060A3B]">
                    Review
                </a>
            </div>
        </div>
        @empty
        <div class="px-5 py-7 text-center">
            <div class="mx-auto flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M12 22a10 10 0 110-20 10 10 0 010 20z" />
                </svg>
            </div>

            <h4 class="mt-2 text-xs font-bold text-slate-900">
                No waiting reviews
            </h4>

            <p class="mx-auto mt-0.5 max-w-xs text-[11px] font-medium text-slate-500">
                Manual review attempts will appear here.
            </p>
        </div>
        @endforelse
    </div>
</div>