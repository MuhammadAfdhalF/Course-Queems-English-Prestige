<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-emerald-100 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-emerald-800">
                    Module Practice Quiz
                </span>
                @if ($practice)
                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $practice->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $practice->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if ($practice->is_required)
                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                    Required
                </span>
                @endif
                @endif
            </div>

            <h2 class="mt-2 text-2xl font-bold text-slate-800">
                {{ $practice ? $practice->title : 'Module Practice' }}
            </h2>
            <p class="text-xs text-slate-500">
                Module: <span class="font-bold text-slate-700">{{ $module->title }}</span> | Level: <span class="font-bold text-slate-700">{{ $level->name }}</span>
            </p>
        </div>

        @if ($practice)
        <div class="flex flex-wrap items-center gap-2">
            {{-- Edit Practice --}}
            <button
                type="button"
                @click="openEditPracticeDrawer('{{ route('admin.course-management.programs.builder.practices.edit', ['courseProgram' => $courseProgram->id, 'modulePractice' => $practice->id]) }}')"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit Config</span>
            </button>

            {{-- Preview Quiz --}}
            <a
                href="{{ route('admin.course-management.practices.preview', $practice->id) }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 rounded-xl bg-blue-50 px-3.5 py-2 text-xs font-bold text-[var(--color-brand-blue)] transition hover:bg-blue-100">
                <span>Preview</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>

            {{-- Review Attempts --}}
            <a
                href="{{ route('admin.course-management.practice-reviews.index', $practice->id) }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <span>Review Attempts</span> &rarr;
            </a>

            {{-- Delete Practice --}}
            <button
                type="button"
                @click="confirmDelete('practice', '{{ $practice->id }}', '{{ addslashes($practice->title) }}', '{{ route('admin.course-management.programs.builder.practices.destroy', ['courseProgram' => $courseProgram->id, 'modulePractice' => $practice->id]) }}', { level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'practice' })"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100"
                title="Delete Practice">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
        @endif
    </div>

    @if (!$practice)
        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <h4 class="mt-3 text-sm font-bold text-slate-800">No practice configured yet</h4>
            <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">Create a module practice quiz to assess student understanding and track progress.</p>
            <div class="mt-4">
                <button
                    type="button"
                    @click="openCreatePracticeDrawer('{{ route('admin.course-management.programs.builder.practices.store', ['courseProgram' => $courseProgram->id, 'module' => $module->id]) }}')"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Configure Practice</span>
                </button>
            </div>
        </div>
    @else
        @php
            $readiness = app(\App\Services\AssessmentConfigService::class)->getReadinessStatus($practice);
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
        @elseif (!$practice->is_active && $readiness['status'] === 'ready')
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4 text-xs font-semibold text-emerald-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Ready to activate! Total Score ({{ $readiness['total_score'] }}) matches Allocated Question Scores ({{ $readiness['allocated_score'] }}).</span>
            </div>
        </div>
        @elseif (!$practice->is_active)
        <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-4 text-xs font-semibold text-amber-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Incomplete Score Allocation. Allocated: {{ $readiness['allocated_score'] }} / Total: {{ $readiness['total_score'] }} (Remaining: {{ $readiness['remaining_score'] }}). Practice cannot be activated until allocated score equals total score.</span>
            </div>
        </div>
        @endif

        {{-- Score Allocation Metric Cards --}}
        <div class="grid gap-4 sm:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Score</span>
                <p class="mt-2 text-3xl font-black text-slate-800">{{ $readiness['total_score'] }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Allocated Score</span>
                <p class="mt-2 text-3xl font-black text-emerald-700">{{ $readiness['allocated_score'] }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Remaining Score</span>
                <p class="mt-2 text-3xl font-black {{ $readiness['remaining_score'] < 0 ? 'text-rose-600' : 'text-blue-700' }}">{{ $readiness['remaining_score'] }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Readiness Status</span>
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

        @if ($practice->description)
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Practice Instructions</h4>
            <p class="mt-1 text-xs text-slate-700 leading-relaxed">{{ $practice->description }}</p>
        </div>
        @endif

        {{-- Practice Questions Section --}}
        <div class="space-y-4 pt-4 border-t border-slate-200">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Practice Questions</h3>
                    <p class="text-xs text-slate-400">Manage quiz questions for this practice</p>
                </div>

                <div class="flex items-center gap-3">
                    @php
                        $allQList = isset($allPracticeQuestions) && $allPracticeQuestions->count() > 0 ? $allPracticeQuestions : collect($questionsList ?? []);
                    @endphp
                    @if ($allQList->count() > 1)
                        <button
                            type="button"
                            x-show="!reorderMode"
                            @click="startReorder('practice_questions', 'Reorder Practice Questions', '{{ route('admin.course-management.programs.builder.practice-questions.reorder', ['courseProgram' => $courseProgram->id, 'modulePractice' => $practice->id]) }}', {{ json_encode($allQList->map(fn($q) => ['id' => $q->id, 'question' => Str::limit(strip_tags($q->question), 60), 'sort_order' => $q->sort_order])->values()) }})"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-xs hover:bg-slate-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            <span>Reorder Questions</span>
                        </button>
                    @endif

                    <button
                        type="button"
                        x-show="!reorderMode"
                        @click="openCreateQuestionDrawer('{{ route('admin.course-management.programs.builder.questions.store', ['courseProgram' => $courseProgram->id, 'modulePractice' => $practice->id]) }}')"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-3.5 py-2 text-xs font-bold text-white shadow-sm transition hover:opacity-90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>+ Add Question</span>
                    </button>
                </div>
            </div>

            @include('partials.admin.course-management.builder.workspace.reorder-bar')

            @php
                $questionsList = isset($questionsPaginator) ? $questionsPaginator->items() : $practice->questions;
            @endphp

            @if (empty($questionsList) || count($questionsList) === 0)
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center">
                    <p class="text-xs font-semibold text-slate-500">No practice questions added yet.</p>
                    <div class="mt-3">
                        <button
                            type="button"
                            @click="openCreateQuestionDrawer('{{ route('admin.course-management.programs.builder.questions.store', ['courseProgram' => $courseProgram->id, 'modulePractice' => $practice->id]) }}')"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-3.5 py-2 text-xs font-bold text-white shadow-sm hover:opacity-90">
                            <span>Add First Question</span> &rarr;
                        </button>
                    </div>
                </div>
            @else
                <div class="grid gap-3">
                    @foreach ($questionsList as $q)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-blue-200">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-xs font-extrabold text-slate-600 shrink-0">
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
                                        {{ strip_tags($q->question) }}
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
                                    @click="openEditQuestionDrawer('{{ route('admin.course-management.programs.builder.questions.edit', ['courseProgram' => $courseProgram->id, 'modulePracticeQuestion' => $q->id]) }}')"
                                    class="rounded-xl border border-slate-200 p-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition"
                                    title="Edit Question">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                {{-- Delete Question --}}
                                <button
                                    type="button"
                                    @click="confirmDelete('question', '{{ $q->id }}', 'Question #{{ $q->sort_order }}', '{{ route('admin.course-management.programs.builder.questions.destroy', ['courseProgram' => $courseProgram->id, 'modulePracticeQuestion' => $q->id]) }}', { level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'practice' })"
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
                                    @click="selectNode({ level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'practice', page: {{ $questionsPaginator->currentPage() - 1 }} })"
                                    class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-100">
                                    &larr; Previous
                                </button>
                            @endif
                            @if ($questionsPaginator->hasMorePages())
                                <button
                                    type="button"
                                    @click="selectNode({ level: '{{ $level->id }}', module: '{{ $module->id }}', exam: null, tab: 'practice', page: {{ $questionsPaginator->currentPage() + 1 }} })"
                                    class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-100">
                                    Next &rarr;
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    @endif
</div>
