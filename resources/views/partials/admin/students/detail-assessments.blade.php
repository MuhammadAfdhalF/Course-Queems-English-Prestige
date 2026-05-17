<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-xl font-black text-slate-900">
            Assessment Summary
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Latest practice and final exam attempts.
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
                <div class="px-6 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-bold text-slate-900">
                                {{ $attempt->practice?->title ?? 'Module Practice' }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
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

                    <p class="mt-3 text-sm font-bold text-slate-700">
                        Score: {{ $attempt->total_score !== null ? number_format((float) $attempt->total_score, 2) . '%' : '-' }}
                    </p>
                </div>
                @empty
                <div class="px-6 py-10 text-center text-sm text-slate-500">
                    No practice attempts yet.
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
                <div class="px-6 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-bold text-slate-900">
                                {{ $attempt->finalExam?->title ?? 'Final Exam' }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                {{ $attempt->finalExam?->courseLevel?->name ?? '-' }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
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

                    <p class="mt-3 text-sm font-bold text-slate-700">
                        Score: {{ $attempt->total_score !== null ? number_format((float) $attempt->total_score, 2) . '%' : '-' }}
                    </p>
                </div>
                @empty
                <div class="px-6 py-10 text-center text-sm text-slate-500">
                    No final exam attempts yet.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin.table-card>