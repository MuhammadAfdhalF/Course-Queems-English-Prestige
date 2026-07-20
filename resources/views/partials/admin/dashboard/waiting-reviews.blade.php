<x-admin.table-card class="overflow-hidden">
    <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50/70 px-6 py-5">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                Review Inbox
            </p>

            <h3 class="mt-1 text-2xl font-black text-slate-900">
                Waiting Reviews
            </h3>

            <p class="mt-1 text-sm font-semibold text-slate-500">
                Practice and final exam attempts waiting for review.
            </p>
        </div>

        <a href="{{ route('admin.course-management.programs.index') }}" class="text-sm font-black text-[var(--color-brand-blue)] hover:underline">
            Open courses
        </a>
    </div>

    <div class="divide-y divide-slate-100">
        @forelse ($waitingReviewItems as $item)
        <div class="p-5 transition hover:bg-slate-50">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex rounded-full {{ $item['type'] === 'Final Exam' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700' }} px-3 py-1 text-xs font-black">
                            {{ $item['type'] }}
                        </span>

                        <span class="text-xs font-black uppercase tracking-[0.14em] text-slate-400">
                            {{ $item['submittedAt'] }}
                        </span>
                    </div>

                    <h4 class="mt-3 text-base font-black text-slate-900">
                        {{ $item['assessment'] }}
                    </h4>

                    <p class="mt-1 text-sm font-bold text-slate-600">
                        {{ $item['student'] }}
                    </p>

                    @if ($item['course'])
                    <p class="mt-1 text-sm font-semibold text-slate-400">
                        {{ $item['course'] }}
                    </p>
                    @endif
                </div>

                <a
                    href="{{ $item['href'] }}"
                    class="inline-flex h-10 shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-xs font-black text-white transition hover:opacity-90">
                    Review
                </a>
            </div>
        </div>
        @empty
        <div class="px-6 py-12 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4M12 22a10 10 0 110-20 10 10 0 010 20z" />
                </svg>
            </div>

            <h4 class="mt-4 text-base font-black text-slate-900">
                No waiting reviews
            </h4>

            <p class="mx-auto mt-2 max-w-sm text-sm font-semibold leading-6 text-slate-500">
                Manual review attempts will appear here.
            </p>
        </div>
        @endforelse
    </div>
</x-admin.table-card>