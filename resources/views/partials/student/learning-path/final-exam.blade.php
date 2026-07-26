@php
    $sections = $finalExamSections ?? ($finalExam ? collect([
        [
            'exam' => $finalExam,
            'id' => $finalExam->id,
            'title' => $finalExam->title,
            'description' => $finalExam->description,
            'grading_method' => $finalExam->grading_method,
            'max_attempts' => $finalExam->max_attempts,
            'sort_order' => $finalExam->sort_order,
            'active_questions_count' => $finalExam->questions_count ?? 1,
            'has_active_questions' => true,
            'status' => match ($latestFinalExamAttempt?->status) {
                'passed' => 'passed',
                'waiting_review' => 'waiting_review',
                'failed' => 'failed',
                default => 'not_started',
            },
            'score' => in_array($latestFinalExamAttempt?->status, ['passed', 'failed'], true) ? (float) $latestFinalExamAttempt->total_score : null,
            'attempts_used' => $finalExamAttemptCount ?? 0,
            'attempts_remaining' => $finalExam->max_attempts ? max(0, $finalExam->max_attempts - ($finalExamAttemptCount ?? 0)) : null,
            'is_attempt_limit_reached' => $finalExam->max_attempts && ($finalExamAttemptCount ?? 0) >= $finalExam->max_attempts,
            'is_exempt_new_section' => false,
            'can_start' => $isFinalExamUnlocked && !$latestFinalExamAttempt && (!$finalExam->max_attempts || ($finalExamAttemptCount ?? 0) < $finalExam->max_attempts),
            'can_continue' => false,
            'can_retake' => $isFinalExamUnlocked && $canRetakeFinalExam && $latestFinalExamAttempt?->status !== 'passed',
            'latest_attempt' => $latestFinalExamAttempt,
            'start_href' => route('student.final-exam', ['enrollment' => $enrollment, 'finalExam' => $finalExam]),
            'result_href' => $latestFinalExamAttempt ? route('student.final-exam-result', ['enrollment' => $enrollment, 'attempt' => $latestFinalExamAttempt]) : null,
        ]
    ]) : collect());
@endphp

@if ($sections->isNotEmpty())
<div class="reveal reveal-delay-2 space-y-6">
    <div class="flex flex-col gap-2">
        <h2 class="text-2xl font-bold text-slate-900">
            Final Exam Sections
        </h2>
        <p class="text-sm text-slate-500">
            Complete all active Final Exam sections to finish this course and earn your certificate.
        </p>
    </div>

    <div class="space-y-6">
        @foreach ($sections as $index => $item)
        <div class="relative overflow-hidden rounded-[26px] bg-gradient-to-r from-[#071738] via-[#081B4A] to-[#0A2059] px-7 py-8 text-white shadow-[0_18px_45px_rgba(15,23,42,0.18)]">
            <div class="relative z-10 flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-slate-200">
                            Section {{ $index + 1 }}
                        </span>

                        <span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-[var(--color-brand-gold)] ring-1 ring-white/10">
                            @if (($item['result_mode'] ?? 'pass_fail') === 'pass_fail' && isset($item['passing_score']) && $item['passing_score'] !== null)
                                Passing: {{ number_format((float) $item['passing_score'], 2) }} / {{ number_format((float) ($item['total_score'] ?? 100), 2) }}
                            @else
                                Score Only Mode
                            @endif
                        </span>

                        @if ($item['max_attempts'])
                        <span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-slate-200 ring-1 ring-white/10">
                            Max attempts: {{ $item['max_attempts'] }}
                        </span>
                        @endif

                        @if ($item['status'] === 'passed')
                        <span class="inline-flex rounded-full bg-emerald-400/15 px-3 py-1 text-xs font-bold text-emerald-200 ring-1 ring-emerald-300/20">
                            Passed · Score {{ number_format((float) $item['score'], 2) }}%
                        </span>
                        @elseif ($item['status'] === 'waiting_review')
                        <span class="inline-flex rounded-full bg-amber-400/15 px-3 py-1 text-xs font-bold text-amber-200 ring-1 ring-amber-300/20">
                            Waiting for Review
                        </span>
                        @elseif ($item['status'] === 'in_progress')
                        <span class="inline-flex rounded-full bg-blue-400/15 px-3 py-1 text-xs font-bold text-blue-200 ring-1 ring-blue-300/20">
                            In Progress
                        </span>
                        @elseif ($item['status'] === 'failed')
                        <span class="inline-flex rounded-full bg-rose-400/15 px-3 py-1 text-xs font-bold text-rose-200 ring-1 ring-rose-300/20">
                            Failed · Score {{ number_format((float) $item['score'], 2) }}%
                        </span>
                        @else
                        <span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-slate-300 ring-1 ring-white/10">
                            Not Started
                        </span>
                        @endif
                    </div>

                    <h3 class="mt-4 text-3xl font-bold leading-tight text-white">
                        {{ $item['title'] }}
                    </h3>

                    @if ($item['description'])
                    <div class="rich-text-content rich-text-content-dark mt-4 max-w-2xl text-sm leading-6 text-slate-200">
                        {!! $item['description'] !!}
                    </div>
                    @endif

                    <div class="mt-4 flex flex-wrap items-center gap-4 text-xs text-slate-300">
                        <span>Attempts used: <strong>{{ $item['attempts_used'] }}</strong>{{ $item['max_attempts'] ? ' / ' . $item['max_attempts'] : '' }}</span>
                        @if ($item['attempts_remaining'] !== null)
                        <span>Remaining: <strong>{{ $item['attempts_remaining'] }}</strong></span>
                        @endif
                    </div>

                    @if (! $isFinalExamUnlocked)
                    <p class="mt-4 text-xs font-semibold leading-5 text-blue-200">
                        Complete all learning modules to unlock this final exam section.
                    </p>
                    @elseif (! $item['has_active_questions'])
                    <p class="mt-4 text-xs font-semibold leading-5 text-amber-200">
                        This exam section is temporarily unavailable.
                    </p>
                    @elseif ($item['status'] === 'waiting_review')
                    <p class="mt-4 text-xs font-semibold leading-5 text-amber-200">
                        Your submission for this section is waiting for admin review.
                    </p>
                    @elseif ($item['status'] === 'passed')
                    <p class="mt-4 text-xs font-semibold leading-5 text-emerald-200">
                        You passed this section.
                    </p>
                    @elseif ($item['is_attempt_limit_reached'])
                    <p class="mt-4 text-xs font-semibold leading-5 text-slate-300">
                        You have reached the maximum number of attempts for this section.
                    </p>
                    @endif
                </div>

                <div class="flex shrink-0 flex-col gap-3">
                    @if (! $isFinalExamUnlocked)
                    <button
                        type="button"
                        disabled
                        class="inline-flex h-12 min-w-[200px] cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-white/50 px-6 text-base font-bold text-slate-400 shadow-md">
                        Locked
                    </button>
                    @elseif (! $item['has_active_questions'])
                    <button
                        type="button"
                        disabled
                        class="inline-flex h-12 min-w-[200px] cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-white/50 px-6 text-base font-bold text-slate-400 shadow-md">
                        Unavailable
                    </button>
                    @elseif ($item['can_continue'])
                    <a
                        href="{{ $item['start_href'] }}"
                        class="inline-flex h-12 min-w-[200px] items-center justify-center gap-2 rounded-xl bg-blue-500 px-6 text-base font-bold text-white shadow-lg transition hover:bg-blue-600">
                        Continue Exam
                    </a>
                    @elseif ($item['can_retake'])
                    <a
                        href="{{ $item['start_href'] }}"
                        class="inline-flex h-12 min-w-[200px] items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-gold)] px-6 text-base font-bold text-white shadow-lg transition hover:opacity-90">
                        Retake Exam
                    </a>
                    @elseif ($item['can_start'])
                    <a
                        href="{{ $item['start_href'] }}"
                        class="inline-flex h-12 min-w-[200px] items-center justify-center gap-2 rounded-xl bg-white px-6 text-base font-bold text-[var(--color-brand-blue)] shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                        Start Exam
                    </a>
                    @endif

                    @if ($item['result_href'])
                    <a
                        href="{{ $item['result_href'] }}"
                        class="inline-flex h-12 min-w-[200px] items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-6 text-base font-bold text-white shadow-sm transition hover:bg-white/20">
                        View Result
                    </a>
                    @endif
                </div>
            </div>

            <div class="pointer-events-none absolute -right-10 top-8 h-40 w-40 rotate-12 rounded-[32px] border-[14px] border-white/10"></div>
        </div>
        @endforeach
    </div>
</div>
@endif