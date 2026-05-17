<x-admin.table-card>
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
        <div>
            <h3 class="text-2xl font-bold text-slate-900">
                Waiting Reviews
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Practice and final exam attempts waiting for review.
            </p>
        </div>

        <a href="{{ route('admin.course-management.programs.index') }}" class="text-sm font-semibold text-[var(--color-brand-blue)]">
            Open courses
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead class="border-b border-slate-200 bg-slate-50">
                <tr class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Student</th>
                    <th class="px-6 py-4">Assessment</th>
                    <th class="px-6 py-4">Submitted</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse ($waitingReviewItems as $item)
                <tr class="transition hover:bg-slate-50">
                    <td class="whitespace-nowrap px-6 py-5">
                        <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                            {{ $item['type'] }}
                        </span>
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 font-semibold text-slate-900">
                        {{ $item['student'] }}
                    </td>

                    <td class="px-6 py-5">
                        <p class="font-semibold text-slate-900">
                            {{ $item['assessment'] }}
                        </p>

                        @if ($item['course'])
                        <p class="mt-1 text-xs text-slate-400">
                            {{ $item['course'] }}
                        </p>
                        @endif
                    </td>

                    <td class="whitespace-nowrap px-6 py-5 text-slate-500">
                        {{ $item['submittedAt'] }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-5">
                        <a
                            href="{{ $item['href'] }}"
                            class="inline-flex h-9 items-center justify-center rounded-lg bg-[var(--color-brand-blue)] px-4 text-xs font-bold text-white transition hover:opacity-90">
                            Review
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <h4 class="text-base font-extrabold text-slate-900">
                            No waiting reviews
                        </h4>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Manual review attempts will appear here.
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin.table-card>