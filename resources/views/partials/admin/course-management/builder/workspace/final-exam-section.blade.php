<div class="space-y-6">
    @php
        $activeQuestionsCount = $exam->questions()->where('is_active', true)->count();
        $isReadyToActivate = !$exam->is_active && $activeQuestionsCount > 0;
    @endphp

    {{-- Header Section --}}
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-purple-100 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-purple-800">
                    Final Exam Section
                </span>
                @if ($exam->is_active)
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-bold text-emerald-700">
                        Active
                    </span>
                @elseif ($isReadyToActivate)
                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                        Ready to activate
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-500">
                        Inactive
                    </span>
                @endif
            </div>

            <h2 class="mt-2 text-2xl font-bold text-slate-800">
                {{ $exam->title }}
            </h2>
            <p class="text-xs text-slate-500">
                Level: <span class="font-bold text-slate-700">{{ $level->name }}</span> | Sort Order: <span class="font-bold text-slate-700">#{{ $exam->sort_order }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Toggle Active Button --}}
            <button
                type="button"
                @click="toggleFinalExamSectionActive('{{ route('admin.course-management.programs.builder.final-exams.toggle-active', ['courseProgram' => $courseProgram->id, 'finalExam' => $exam->id]) }}')"
                class="inline-flex items-center gap-1.5 rounded-xl border px-3.5 py-2 text-xs font-bold shadow-sm transition {{ $exam->is_active ? 'border-amber-200 bg-amber-50 text-amber-800 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                <span>{{ $exam->is_active ? 'Deactivate Section' : 'Activate Section' }}</span>
            </button>

            {{-- Edit Section --}}
            <button
                type="button"
                @click="openEditFinalExamSectionDrawer('{{ route('admin.course-management.programs.builder.final-exams.edit', ['courseProgram' => $courseProgram->id, 'finalExam' => $exam->id]) }}')"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit Section</span>
            </button>

            {{-- Preview Section --}}
            <a
                href="{{ route('admin.course-management.final-exams.preview', $exam->id) }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 rounded-xl bg-purple-50 px-3.5 py-2 text-xs font-bold text-purple-700 transition hover:bg-purple-100">
                <span>Preview</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>

            {{-- Review Attempts --}}
            <a
                href="{{ route('admin.course-management.final-exam-reviews.index', $exam->id) }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <span>Review Attempts</span> &rarr;
            </a>

            {{-- Delete Section --}}
            <button
                type="button"
                @click="confirmDelete('final_exam_section', '{{ $exam->id }}', '{{ addslashes($exam->title) }}', '{{ route('admin.course-management.programs.builder.final-exams.destroy', ['courseProgram' => $courseProgram->id, 'finalExam' => $exam->id]) }}', { level: '{{ $level->id }}', module: null, exam: null, tab: 'final-exam' })"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100"
                title="Delete Section">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>

    @php
        $readiness = app(\App\Services\AssessmentConfigService::class)->getReadinessStatus($exam);
    @endphp

    {{-- Score Allocation & Readiness Banner --}}
    @if ($readiness['status'] === 'invalid_over_allocated')
    <div class="rounded-2xl border border-rose-200 bg-rose-50/70 p-4 text-xs font-semibold text-rose-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Over-Allocated Configuration! Allocated score ({{ $readiness['allocated_score'] }}) exceeds configured Total Score ({{ $readiness['total_score'] }}). Please adjust question scores.</span>
        </div>
    </div>
    @elseif (!$exam->is_active && $readiness['status'] === 'ready')
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4 text-xs font-semibold text-emerald-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Ready to activate! Total Score ({{ $readiness['total_score'] }}) matches Allocated Question Scores ({{ $readiness['allocated_score'] }}).</span>
        </div>
        <button
            type="button"
            @click="toggleFinalExamSectionActive('{{ route('admin.course-management.programs.builder.final-exams.toggle-active', ['courseProgram' => $courseProgram->id, 'finalExam' => $exam->id]) }}')"
            class="rounded-xl bg-emerald-700 px-3 py-1 text-xs font-bold text-white shadow-sm hover:bg-emerald-800 shrink-0">
            Activate Now
        </button>
    </div>
    @elseif (!$exam->is_active)
    <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-4 text-xs font-semibold text-amber-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Incomplete Score Allocation. Allocated: {{ $readiness['allocated_score'] }} / Total: {{ $readiness['total_score'] }} (Remaining: {{ $readiness['remaining_score'] }}). Section cannot be activated until allocated score equals total score.</span>
        </div>
    </div>
    @endif

    {{-- Score Allocation Metric Cards --}}
    <div class="grid gap-4 sm:grid-cols-4">
        <div class="rounded-2xl border border-purple-100 bg-purple-50/50 p-4 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Total Score</span>
            <p class="mt-2 text-3xl font-black text-purple-900">{{ $readiness['total_score'] }}</p>
        </div>

        <div class="rounded-2xl border border-purple-100 bg-purple-50/50 p-4 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Allocated Score</span>
            <p class="mt-2 text-3xl font-black text-purple-900">{{ $readiness['allocated_score'] }}</p>
        </div>

        <div class="rounded-2xl border border-purple-100 bg-purple-50/50 p-4 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Remaining Score</span>
            <p class="mt-2 text-3xl font-black {{ $readiness['remaining_score'] < 0 ? 'text-rose-600' : 'text-purple-900' }}">{{ $readiness['remaining_score'] }}</p>
        </div>

        <div class="rounded-2xl border border-purple-100 bg-purple-50/50 p-4 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Readiness Status</span>
            <p class="mt-2 text-sm font-black uppercase tracking-wider">
                @if ($readiness['status'] === 'ready')
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800">Ready to Activate</span>
                @elseif ($readiness['status'] === 'invalid_over_allocated')
                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-bold text-rose-800">Over Allocated</span>
                @else
                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">Incomplete</span>
                @endif
            </p>
        </div>
    </div>

    @if ($exam->description)
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Section Description / Instructions</h4>
        <div class="rich-text-content text-xs sm:text-sm text-slate-700 leading-relaxed">
            {!! $exam->description !!}
        </div>
    </div>
    @endif

    {{-- Questions Section --}}
    <div class="space-y-4 pt-4 border-t border-slate-200">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Final Exam Questions</h3>
                <p class="text-xs text-slate-400">Manage questions for this exam section</p>
            </div>

            <div class="flex items-center gap-3">
                @php
                    $allEqList = isset($allExamQuestions) && $allExamQuestions->count() > 0 ? $allExamQuestions : collect($questionsList ?? []);
                @endphp
                @if ($exam->questions->count() > 1)
                    <button
                        type="button"
                        x-show="!reorderMode"
                        @click="startReorder('final_exam_questions', 'Reorder Final Exam Questions', '{{ route('admin.course-management.programs.builder.final-exam-questions.reorder', ['courseProgram' => $courseProgram->id, 'finalExam' => $exam->id]) }}', {{ json_encode($allEqList->map(fn($q) => ['id' => $q->id, 'question' => \App\Support\RichText::toPlainText($q->question, 60), 'sort_order' => $q->sort_order])->values()) }})"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-2xs hover:bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span>Reorder Questions</span>
                    </button>
                @endif

                <button
                    type="button"
                    x-show="!reorderMode"
                    @click="openCreateFinalExamQuestionDrawer('{{ route('admin.course-management.programs.builder.final-exam-questions.store', ['courseProgram' => $courseProgram->id, 'finalExam' => $exam->id]) }}', { total_score: {{ (float) $readiness['total_score'] }}, allocated_score: {{ (float) $readiness['allocated_score'] }}, remaining_score: {{ (float) $readiness['remaining_score'] }} })"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-purple-700 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-purple-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Add Question</span>
                </button>
            </div>
        </div>

        @include('partials.admin.course-management.builder.workspace.reorder-bar')

        @php
            $questionsList = isset($questionsPaginator) ? $questionsPaginator->items() : $exam->questions;
        @endphp

        @if (empty($questionsList) || count($questionsList) === 0)
            <div class="rounded-2xl border border-dashed border-purple-200 bg-purple-50/50 p-6 text-center">
                <p class="text-xs font-semibold text-purple-800">No exam questions added yet.</p>
                <div class="mt-3">
                    <button
                        type="button"
                        @click="openCreateFinalExamQuestionDrawer('{{ route('admin.course-management.programs.builder.final-exam-questions.store', ['courseProgram' => $courseProgram->id, 'finalExam' => $exam->id]) }}', { total_score: {{ (float) $readiness['total_score'] }}, allocated_score: {{ (float) $readiness['allocated_score'] }}, remaining_score: {{ (float) $readiness['remaining_score'] }} })"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-purple-700 px-3.5 py-2 text-xs font-bold text-white shadow-sm hover:bg-purple-800">
                        <span>Add First Question</span> &rarr;
                    </button>
                </div>
            </div>
        @else
            <div class="grid gap-3">
                @foreach ($questionsList as $q)
                <div class="rounded-2xl border border-purple-100 bg-white p-4 shadow-2xs transition hover:border-purple-200">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start gap-3">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-purple-100 text-xs font-extrabold text-purple-800 shrink-0">
                                #{{ $q->sort_order }}
                            </span>

                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-purple-700">
                                        {{ str_replace('_', ' ', $q->question_type) }}
                                    </span>
                                    <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-700">
                                        {{ $q->score }} Pts
                                    </span>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $q->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $q->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                <h4 class="mt-1 text-sm font-bold text-slate-900 line-clamp-2">
                                    {{ \App\Support\RichText::toPlainText($q->question, 120) }}
                                </h4>

                                @if ($q->question_type === 'multiple_choice' && $q->options->isNotEmpty())
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    @foreach ($q->options as $opt)
                                    <span class="inline-flex items-center gap-1 rounded-md border px-2 py-0.5 text-[11px] font-semibold {{ $opt->is_correct ? 'border-emerald-300 bg-emerald-50 text-emerald-800 font-bold' : 'border-slate-200 bg-slate-50 text-slate-600' }}">
                                        <span>{{ $opt->option_label }}:</span>
                                        <span>{{ Str::limit($opt->option_text, 30) }}</span>
                                        @if ($opt->is_correct) &check; @endif
                                    </span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2 border-t border-slate-100 pt-3 sm:border-t-0 sm:pt-0 shrink-0">
                            {{-- Edit Question --}}
                            <button
                                type="button"
                                @click="openEditFinalExamQuestionDrawer('{{ route('admin.course-management.programs.builder.final-exam-questions.edit', ['courseProgram' => $courseProgram->id, 'finalExamQuestion' => $q->id]) }}', { total_score: {{ (float) $readiness['total_score'] }}, allocated_score: {{ (float) $readiness['allocated_score'] }}, remaining_score: {{ (float) $readiness['remaining_score'] }} })"
                                class="rounded-xl border border-slate-200 p-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition"
                                title="Edit Question">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            {{-- Delete Question --}}
                            <button
                                type="button"
                                @click="confirmDelete('final_exam_question', '{{ $q->id }}', 'Question #{{ $q->sort_order }}', '{{ route('admin.course-management.programs.builder.final-exam-questions.destroy', ['courseProgram' => $courseProgram->id, 'finalExamQuestion' => $q->id]) }}', { level: '{{ $level->id }}', module: null, exam: '{{ $exam->id }}', tab: 'questions' })"
                                class="rounded-xl bg-rose-50 p-1.5 text-rose-600 hover:bg-rose-100 transition"
                                title="Delete Question">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if (isset($questionsPaginator) && $questionsPaginator->hasPages())
                <div class="flex items-center justify-between border-t border-slate-200 pt-4 text-xs font-semibold">
                    <span class="text-slate-500">Page {{ $questionsPaginator->currentPage() }} of {{ $questionsPaginator->lastPage() }}</span>
                    <div class="flex items-center gap-2">
                        @if (!$questionsPaginator->onFirstPage())
                            <button
                                type="button"
                                @click="selectNode({ level: '{{ $level->id }}', module: null, exam: '{{ $exam->id }}', tab: 'questions', page: {{ $questionsPaginator->currentPage() - 1 }} })"
                                class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-100">
                                &larr; Previous
                            </button>
                        @endif
                        @if ($questionsPaginator->hasMorePages())
                            <button
                                type="button"
                                @click="selectNode({ level: '{{ $level->id }}', module: null, exam: '{{ $exam->id }}', tab: 'questions', page: {{ $questionsPaginator->currentPage() + 1 }} })"
                                class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-100">
                                Next &rarr;
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
