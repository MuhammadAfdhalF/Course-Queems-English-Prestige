<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-xl font-black text-slate-900">
            Assessment Summary
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Latest practice and final exam attempts. Waiting review items should be checked by admin.
        </p>
    </div>

    <div class="grid gap-0 xl:grid-cols-2">
        <div class="border-b border-slate-200 xl:border-b-0 xl:border-r">
            <div class="bg-slate-50 px-6 py-4">
                <h3 class="text-sm font-black uppercase tracking-[0.16em] text-slate-500">
                    Practice Attempts
                </h3>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($practiceAttempts as $attempt)
                <div class="px-6 py-5 transition hover:bg-slate-50/70">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-black text-slate-900">
                                {{ $attempt->practice?->title ?? 'Module Practice' }}
                            </p>

                            <p class="mt-1 text-xs font-semibold text-slate-400">
                                Submitted: {{ $attempt->submitted_at?->format('d M Y H:i') ?? '-' }}
                            </p>
                        </div>

                        @php
                        $variant = match ($attempt->status) {
                        'passed', 'graded' => 'completed',
                        'failed' => 'rejected',
                        'waiting_review' => 'pending',
                        default => 'pending',
                        };
                        @endphp

                        <x-admin.status-badge :variant="$variant">
                            {{ str_replace('_', ' ', ucfirst($attempt->status)) }}
                        </x-admin.status-badge>
                    </div>

                    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm font-black text-slate-700">
                            Score: {{ $attempt->total_score !== null ? number_format((float) $attempt->total_score, 2) . '%' : '-' }}
                        </p>

                        <a
                            href="{{ route('admin.course-management.practice-reviews.show', $attempt) }}"
                            class="inline-flex h-9 items-center justify-center rounded-lg {{ $attempt->status === 'waiting_review' ? 'bg-amber-500 text-white' : 'bg-[var(--color-brand-blue)] text-white' }} px-4 text-xs font-black transition hover:opacity-90">
                            {{ $attempt->status === 'waiting_review' ? 'Review Now' : 'View Review' }}
                        </a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-sm font-black text-slate-700">No practice attempts yet</p>
                    <p class="mt-1 text-sm text-slate-500">
                        Practice submissions will appear after the student completes module practice.
                    </p>
                </div>
                @endforelse
            </div>
        </div>

        <div>
            <div class="bg-slate-50 px-6 py-4">
                <h3 class="text-sm font-black uppercase tracking-[0.16em] text-slate-500">
                    Final Exam Attempts
                </h3>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($finalExamAttempts as $attempt)
                <div class="px-6 py-5 transition hover:bg-slate-50/70">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-black text-slate-900">
                                {{ $attempt->finalExam?->title ?? 'Final Exam' }}
                            </p>

                            <p class="mt-1 text-xs font-semibold text-slate-400">
                                {{ $attempt->finalExam?->courseLevel?->name ?? '-' }}
                            </p>

                            <p class="mt-1 text-xs font-semibold text-slate-400">
                                Submitted: {{ $attempt->submitted_at?->format('d M Y H:i') ?? '-' }}
                            </p>
                        </div>

                        @php
                        $variant = match ($attempt->status) {
                        'passed', 'graded' => 'completed',
                        'failed' => 'rejected',
                        'waiting_review' => 'pending',
                        default => 'pending',
                        };
                        @endphp

                        <x-admin.status-badge :variant="$variant">
                            {{ str_replace('_', ' ', ucfirst($attempt->status)) }}
                        </x-admin.status-badge>
                    </div>

                    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm font-black text-slate-700">
                            Score: {{ $attempt->total_score !== null ? number_format((float) $attempt->total_score, 2) . '%' : '-' }}
                        </p>

                        <a
                            href="{{ route('admin.course-management.final-exam-reviews.show', $attempt) }}"
                            class="inline-flex h-9 items-center justify-center rounded-lg {{ $attempt->status === 'waiting_review' ? 'bg-amber-500 text-white' : 'bg-[var(--color-brand-blue)] text-white' }} px-4 text-xs font-black transition hover:opacity-90">
                            {{ $attempt->status === 'waiting_review' ? 'Review Now' : 'View Review' }}
                        </a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-sm font-black text-slate-700">No final exam attempts yet</p>
                    <p class="mt-1 text-sm text-slate-500">
                        Final exam attempts will appear after the student submits a final exam.
                    </p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin.table-card>