<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseProgram;
use App\Models\FinalExam;
use App\Models\FinalExamQuestion;
use App\Services\AssessmentConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinalExamQuestionController extends Controller
{
    public function __construct(
        protected AssessmentConfigService $configService
    ) {}

    public function index(FinalExam $finalExam)
    {
        $finalExam->load('courseLevel.courseProgram');

        $questions = $finalExam->questions()
            ->with('options')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('pages.admin.course-management.final-exam-questions.index', compact(
            'finalExam',
            'questions'
        ));
    }

    public function create(Request $request, FinalExam $finalExam)
    {
        $finalExam->load('courseLevel.courseProgram');

        $nextSortOrder = ((int) $finalExam->questions()->max('sort_order')) + 1;
        $activateWhenReady = $request->boolean('activate_when_ready');

        return view('pages.admin.course-management.final-exam-questions.create', compact(
            'finalExam',
            'nextSortOrder',
            'activateWhenReady'
        ));
    }

    // ==========================================
    // SCOPED BUILDER AJAX ENDPOINTS (PHASE E)
    // ==========================================

    public function builderStore(Request $request, CourseProgram $courseProgram, FinalExam $finalExam)
    {
        $finalExam->load('courseLevel');

        if ($finalExam->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: exam section does not belong to this program.'
            ], 403);
        }

        $this->configService->ensureNotLocked($finalExam);

        $validated = $this->validateQuestion($request);
        $questionIsActive = $request->boolean('is_active');
        $questionScore = (float) ($validated['score'] ?? 0);

        $this->configService->validateProspectiveScore($finalExam, $questionScore, $questionIsActive);

        $question = DB::transaction(function () use ($finalExam, $validated, $questionIsActive, $questionScore) {
            $lockedExam = FinalExam::whereKey($finalExam->id)->lockForUpdate()->firstOrFail();
            $this->configService->ensureNotLocked($lockedExam);
            $this->configService->validateProspectiveScore($lockedExam, $questionScore, $questionIsActive);

            $sortOrder = $validated['sort_order'] ?? (((int) $lockedExam->questions()->max('sort_order')) + 1);

            $q = $lockedExam->questions()->create([
                'question_type' => $validated['question_type'],
                'question' => $validated['question'],
                'explanation' => $validated['explanation'] ?? null,
                'score' => $questionScore,
                'sort_order' => $sortOrder,
                'is_active' => $questionIsActive,
            ]);

            if ($validated['question_type'] === 'multiple_choice') {
                $this->saveOptions($q, $validated['options'], $validated['correct_option']);
            }

            return $q;
        });

        $deactivated = $this->configService->handlePostMutationDeactivation($finalExam);
        $message = $deactivated
            ? 'Final Exam question created. Section was deactivated because active question scores no longer match total score.'
            : 'Final Exam question created successfully.';

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'deactivated' => $deactivated,
            'question_id' => $question->id,
            'redirect_node' => [
                'level' => $finalExam->course_level_id,
                'module' => null,
                'exam' => $finalExam->id,
                'tab' => 'questions'
            ]
        ]);
    }

    public function builderEdit(Request $request, CourseProgram $courseProgram, FinalExamQuestion $finalExamQuestion)
    {
        $finalExamQuestion->load('finalExam.courseLevel', 'options');

        if ($finalExamQuestion->finalExam?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: question does not belong to this program.'
            ], 403);
        }

        $optionsDict = [];
        $correctOption = null;
        foreach ($finalExamQuestion->options as $opt) {
            $optionsDict[$opt->option_label] = $opt->option_text;
            if ($opt->is_correct) {
                $correctOption = $opt->option_label;
            }
        }

        $finalExam = $finalExamQuestion->finalExam;
        $readiness = $this->configService->getReadinessStatus($finalExam);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $finalExamQuestion->id,
                'final_exam_id' => $finalExamQuestion->final_exam_id,
                'question_type' => $finalExamQuestion->question_type,
                'question' => $finalExamQuestion->question,
                'explanation' => $finalExamQuestion->explanation,
                'score' => (float) $finalExamQuestion->score,
                'sort_order' => $finalExamQuestion->sort_order,
                'is_active' => (bool) $finalExamQuestion->is_active,
                'is_locked' => $this->configService->hasHistory($finalExam),
                'options' => $optionsDict,
                'correct_option' => $correctOption,
                'allocation' => [
                    'total_score' => (float) $readiness['total_score'],
                    'allocated_score' => (float) $readiness['allocated_score'],
                    'remaining_score' => (float) $readiness['remaining_score'],
                ],
                'update_url' => route('admin.course-management.programs.builder.final-exam-questions.update', ['courseProgram' => $courseProgram->id, 'finalExamQuestion' => $finalExamQuestion->id]),
            ]
        ]);
    }

    public function builderUpdate(Request $request, CourseProgram $courseProgram, FinalExamQuestion $finalExamQuestion)
    {
        $finalExamQuestion->load('finalExam.courseLevel');

        if ($finalExamQuestion->finalExam?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: question does not belong to this program.'
            ], 403);
        }

        $finalExam = $finalExamQuestion->finalExam;
        $this->configService->ensureNotLocked($finalExam);

        $validated = $this->validateQuestion($request);
        $questionIsActive = $request->boolean('is_active');
        $questionScore = (float) ($validated['score'] ?? 0);

        $this->configService->validateProspectiveScore($finalExam, $questionScore, $questionIsActive, $finalExamQuestion);

        DB::transaction(function () use ($finalExamQuestion, $finalExam, $validated, $request, $questionIsActive, $questionScore) {
            $lockedExam = FinalExam::whereKey($finalExam->id)->lockForUpdate()->firstOrFail();
            $this->configService->ensureNotLocked($lockedExam);
            $this->configService->validateProspectiveScore($lockedExam, $questionScore, $questionIsActive, $finalExamQuestion);

            $finalExamQuestion->update([
                'question_type' => $validated['question_type'],
                'question' => $validated['question'],
                'explanation' => $validated['explanation'] ?? null,
                'score' => $questionScore,
                'sort_order' => $validated['sort_order'] ?? $finalExamQuestion->sort_order,
                'is_active' => $questionIsActive,
            ]);

            if ($validated['question_type'] === 'multiple_choice') {
                $this->saveOptions($finalExamQuestion, $validated['options'], $validated['correct_option']);
            } else {
                $finalExamQuestion->options()->delete();
            }
        });

        $deactivated = $this->configService->handlePostMutationDeactivation($finalExam);
        $message = $deactivated
            ? 'Final Exam question updated. Section was deactivated because active question scores no longer match total score.'
            : 'Final Exam question updated successfully.';

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'deactivated' => $deactivated,
            'question_id' => $finalExamQuestion->id,
            'redirect_node' => [
                'level' => $finalExamQuestion->finalExam->course_level_id,
                'module' => null,
                'exam' => $finalExamQuestion->final_exam_id,
                'tab' => 'questions'
            ]
        ]);
    }

    public function builderDestroy(Request $request, CourseProgram $courseProgram, FinalExamQuestion $finalExamQuestion)
    {
        $finalExamQuestion->load('finalExam.courseLevel');

        if ($finalExamQuestion->finalExam?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: question does not belong to this program.'
            ], 403);
        }

        $finalExam = $finalExamQuestion->finalExam;
        $this->configService->ensureNotLocked($finalExam);

        // Delete Guard: Check if student answers/attempts exist
        if ($this->configService->hasHistory($finalExam)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Questions cannot be changed because this assessment already has student attempts/results.'
            ], 422);
        }

        DB::transaction(function () use ($finalExamQuestion, $finalExam) {
            $lockedExam = FinalExam::whereKey($finalExam->id)->lockForUpdate()->firstOrFail();
            $this->configService->ensureNotLocked($lockedExam);

            $finalExamQuestion->delete();
        });

        $deactivated = $this->configService->handlePostMutationDeactivation($finalExam);
        $message = $deactivated
            ? 'Final Exam question deleted. Section was deactivated because active question scores no longer match total score.'
            : 'Final Exam question deleted successfully.';

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'deactivated' => $deactivated,
            'redirect_node' => [
                'level' => $finalExam->course_level_id,
                'module' => null,
                'exam' => $finalExam->id,
                'tab' => 'questions'
            ]
        ]);
    }

    public function builderReorder(Request $request, CourseProgram $courseProgram, FinalExam $finalExam)
    {
        $finalExam->load('courseLevel');

        if ($finalExam->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: exam section does not belong to this program.'
            ], 403);
        }

        $this->configService->ensureNotLocked($finalExam);

        $validated = $request->validate([
            'ordered_ids' => ['required', 'array'],
            'ordered_ids.*' => ['required', 'integer'],
            'original_ordered_ids' => ['nullable', 'array'],
            'original_ordered_ids.*' => ['integer'],
        ]);

        $orderedIds = array_map('intval', $validated['ordered_ids']);
        $originalOrderedIds = isset($validated['original_ordered_ids']) ? array_map('intval', $validated['original_ordered_ids']) : null;

        try {
            return DB::transaction(function () use ($finalExam, $orderedIds, $originalOrderedIds) {
                // 1. Explicitly execute parent SQL query lock
                $lockedExam = FinalExam::whereKey($finalExam->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $this->configService->ensureNotLocked($lockedExam);

                // 2. Lock & read current server siblings
                $siblings = $lockedExam->questions()
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get(['id', 'sort_order']);

                $currentServerOrderedIds = $siblings->pluck('id')->values()->all();

                $existingIdsCopy = $currentServerOrderedIds;
                $orderedIdsCopy = $orderedIds;
                sort($existingIdsCopy);
                sort($orderedIdsCopy);

                if (count($orderedIds) !== count($currentServerOrderedIds) || $existingIdsCopy !== $orderedIdsCopy || count(array_unique($orderedIds)) !== count($orderedIds)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The item list has changed. Reload the latest order and try again.'
                    ], 422);
                }

                if ($originalOrderedIds !== null && $currentServerOrderedIds !== $originalOrderedIds) {
                    return response()->json([
                        'status' => 'conflict',
                        'message' => 'The order has been changed by another administrator. Reload the latest order and try again.'
                    ], 409);
                }

                foreach ($orderedIds as $index => $id) {
                    FinalExamQuestion::where('id', $id)->update(['sort_order' => $index + 1]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Final Exam questions order updated successfully.'
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            if ($this->isConcurrencyException($e)) {
                return response()->json([
                    'status' => 'conflict',
                    'message' => 'A database concurrency conflict occurred. Reload the latest order and try again.'
                ], 409);
            }
            \Illuminate\Support\Facades\Log::error('Final Exam question reorder query error: ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }

    protected function isConcurrencyException(\Illuminate\Database\QueryException $e): bool
    {
        $sqlState = (string) $e->getCode();
        $driverCode = $e->errorInfo[1] ?? null;

        return $sqlState === '40001' || $driverCode === 1213 || $driverCode === 1205;
    }

    // ==========================================
    // LEGACY FULL-PAGE ENDPOINTS
    // ==========================================

    public function store(Request $request, FinalExam $finalExam)
    {
        $this->configService->ensureNotLocked($finalExam);

        $validated = $this->validateQuestion($request);

        $questionIsActive = $request->boolean('is_active');
        $questionScore = (float) ($validated['score'] ?? 0);

        $this->configService->validateProspectiveScore($finalExam, $questionScore, $questionIsActive);

        $question = DB::transaction(function () use ($finalExam, $validated, $questionIsActive, $questionScore) {
            $lockedExam = FinalExam::whereKey($finalExam->id)->lockForUpdate()->firstOrFail();
            $this->configService->ensureNotLocked($lockedExam);
            $this->configService->validateProspectiveScore($lockedExam, $questionScore, $questionIsActive);

            $q = $lockedExam->questions()->create([
                'question_type' => $validated['question_type'],
                'question' => $validated['question'],
                'explanation' => $validated['explanation'] ?? null,
                'score' => $questionScore,
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $questionIsActive,
            ]);

            if ($validated['question_type'] === 'multiple_choice') {
                $this->saveOptions($q, $validated['options'], $validated['correct_option']);
            }

            return $q;
        });

        $deactivated = $this->configService->handlePostMutationDeactivation($finalExam);
        $message = $deactivated
            ? 'Final exam question created. Section was deactivated because active question scores no longer match total score.'
            : 'Final exam question has been created successfully.';

        return redirect()
            ->route('admin.course-management.final-exams.questions.index', $finalExam)
            ->with($deactivated ? 'warning' : 'success', $message);
    }

    public function edit(FinalExamQuestion $finalExamQuestion)
    {
        $finalExamQuestion->load('finalExam.courseLevel.courseProgram', 'options');

        return view('pages.admin.course-management.final-exam-questions.edit', compact('finalExamQuestion'));
    }

    public function update(Request $request, FinalExamQuestion $finalExamQuestion)
    {
        $finalExam = $finalExamQuestion->finalExam;
        $this->configService->ensureNotLocked($finalExam);

        $validated = $this->validateQuestion($request);

        $questionIsActive = $request->boolean('is_active');
        $questionScore = (float) ($validated['score'] ?? 0);

        $this->configService->validateProspectiveScore($finalExam, $questionScore, $questionIsActive, $finalExamQuestion);

        DB::transaction(function () use ($finalExamQuestion, $finalExam, $validated, $questionIsActive, $questionScore) {
            $lockedExam = FinalExam::whereKey($finalExam->id)->lockForUpdate()->firstOrFail();
            $this->configService->ensureNotLocked($lockedExam);
            $this->configService->validateProspectiveScore($lockedExam, $questionScore, $questionIsActive, $finalExamQuestion);

            $finalExamQuestion->update([
                'question_type' => $validated['question_type'],
                'question' => $validated['question'],
                'explanation' => $validated['explanation'] ?? null,
                'score' => $questionScore,
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $questionIsActive,
            ]);

            if ($validated['question_type'] === 'multiple_choice') {
                $this->saveOptions($finalExamQuestion, $validated['options'], $validated['correct_option']);
            } else {
                $finalExamQuestion->options()->delete();
            }
        });

        $deactivated = $this->configService->handlePostMutationDeactivation($finalExam);
        $message = $deactivated
            ? 'Final exam question updated. Section was deactivated because active question scores no longer match total score.'
            : 'Final exam question has been updated successfully.';

        return redirect()
            ->route('admin.course-management.final-exams.questions.index', $finalExam)
            ->with($deactivated ? 'warning' : 'success', $message);
    }

    public function destroy(FinalExamQuestion $finalExamQuestion)
    {
        $finalExam = $finalExamQuestion->finalExam;
        $this->configService->ensureNotLocked($finalExam);

        DB::transaction(function () use ($finalExamQuestion, $finalExam) {
            $lockedExam = FinalExam::whereKey($finalExam->id)->lockForUpdate()->firstOrFail();
            $this->configService->ensureNotLocked($lockedExam);

            $finalExamQuestion->delete();
        });

        $deactivated = $this->configService->handlePostMutationDeactivation($finalExam);
        $message = $deactivated
            ? 'Final exam question deleted. Section was deactivated because active question scores no longer match total score.'
            : 'Final exam question has been deleted successfully.';

        return redirect()
            ->route('admin.course-management.final-exams.questions.index', $finalExam)
            ->with($deactivated ? 'warning' : 'success', $message);
    }

    public function preview(FinalExam $finalExam)
    {
        $finalExam->load('courseLevel.courseProgram');

        $questions = $finalExam->questions()
            ->with('options')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('pages.admin.course-management.final-exam-questions.preview', compact(
            'finalExam',
            'questions'
        ));
    }

    private function validateQuestion(Request $request): array
    {
        return $request->validate([
            'question_type' => ['required', 'in:multiple_choice,short_answer,essay,upload'],
            'question' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'score' => ['required', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],

            'options' => ['nullable', 'array'],
            'options.A' => ['required_if:question_type,multiple_choice', 'nullable', 'string'],
            'options.B' => ['required_if:question_type,multiple_choice', 'nullable', 'string'],
            'options.C' => ['required_if:question_type,multiple_choice', 'nullable', 'string'],
            'options.D' => ['required_if:question_type,multiple_choice', 'nullable', 'string'],
            'correct_option' => ['required_if:question_type,multiple_choice', 'nullable', 'in:A,B,C,D'],
        ]);
    }

    private function saveOptions(FinalExamQuestion $question, array $options, string $correctOption): void
    {
        foreach (['A', 'B', 'C', 'D'] as $index => $label) {
            $question->options()->updateOrCreate(
                [
                    'option_label' => $label,
                ],
                [
                    'option_text' => $options[$label] ?? '',
                    'is_correct' => $correctOption === $label,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
