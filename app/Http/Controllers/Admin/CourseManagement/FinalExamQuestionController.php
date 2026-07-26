<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseProgram;
use App\Models\FinalExam;
use App\Models\FinalExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinalExamQuestionController extends Controller
{
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

        $validated = $this->validateQuestion($request);
        $questionIsActive = $request->boolean('is_active');

        $question = DB::transaction(function () use ($finalExam, $validated, $questionIsActive) {
            $sortOrder = $validated['sort_order'] ?? (((int) $finalExam->questions()->max('sort_order')) + 1);

            $q = $finalExam->questions()->create([
                'question_type' => $validated['question_type'],
                'question' => $validated['question'],
                'explanation' => $validated['explanation'] ?? null,
                'score' => $validated['score'] ?? 0,
                'sort_order' => $sortOrder,
                'is_active' => $questionIsActive,
            ]);

            if ($validated['question_type'] === 'multiple_choice') {
                $this->saveOptions($q, $validated['options'], $validated['correct_option']);
            }

            return $q;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Final Exam question created successfully.',
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

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $finalExamQuestion->id,
                'final_exam_id' => $finalExamQuestion->final_exam_id,
                'question_type' => $finalExamQuestion->question_type,
                'question' => $finalExamQuestion->question,
                'explanation' => $finalExamQuestion->explanation,
                'score' => $finalExamQuestion->score,
                'sort_order' => $finalExamQuestion->sort_order,
                'is_active' => (bool) $finalExamQuestion->is_active,
                'options' => $optionsDict,
                'correct_option' => $correctOption,
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

        $validated = $this->validateQuestion($request);

        DB::transaction(function () use ($finalExamQuestion, $validated, $request) {
            $finalExamQuestion->update([
                'question_type' => $validated['question_type'],
                'question' => $validated['question'],
                'explanation' => $validated['explanation'] ?? null,
                'score' => $validated['score'] ?? 0,
                'sort_order' => $validated['sort_order'] ?? $finalExamQuestion->sort_order,
                'is_active' => $request->boolean('is_active'),
            ]);

            if ($validated['question_type'] === 'multiple_choice') {
                $this->saveOptions($finalExamQuestion, $validated['options'], $validated['correct_option']);
            } else {
                $finalExamQuestion->options()->delete();
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Final Exam question updated successfully.',
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

        // Delete Guard: Check if student answers exist
        if ($finalExamQuestion->answers()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete question because student answers exist.'
            ], 422);
        }

        $finalExam = $finalExamQuestion->finalExam;
        $finalExamQuestion->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Final Exam question deleted successfully.',
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
        $validated = $this->validateQuestion($request);

        $questionIsActive = $request->boolean('is_active');

        $question = $finalExam->questions()->create([
            'question_type' => $validated['question_type'],
            'question' => $validated['question'],
            'explanation' => $validated['explanation'] ?? null,
            'score' => $validated['score'] ?? 0,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $questionIsActive,
        ]);

        if ($validated['question_type'] === 'multiple_choice') {
            $this->saveOptions($question, $validated['options'], $validated['correct_option']);
        }

        $activateWhenReady = $request->boolean('activate_when_ready');
        $sectionActivated = false;

        if ($activateWhenReady && $questionIsActive && ! $finalExam->is_active) {
            $activeQuestionsCount = $finalExam->questions()->where('is_active', true)->count();
            if ($activeQuestionsCount === 1) {
                $finalExam->update(['is_active' => true]);
                $sectionActivated = true;
            }
        }

        $message = $sectionActivated
            ? 'Question created and Final Exam section activated successfully.'
            : 'Final exam question has been created successfully.';

        return redirect()
            ->route('admin.course-management.final-exams.questions.index', $finalExam)
            ->with('success', $message);
    }

    public function edit(FinalExamQuestion $finalExamQuestion)
    {
        $finalExamQuestion->load('finalExam.courseLevel.courseProgram', 'options');

        return view('pages.admin.course-management.final-exam-questions.edit', compact('finalExamQuestion'));
    }

    public function update(Request $request, FinalExamQuestion $finalExamQuestion)
    {
        $validated = $this->validateQuestion($request);

        $finalExamQuestion->update([
            'question_type' => $validated['question_type'],
            'question' => $validated['question'],
            'explanation' => $validated['explanation'] ?? null,
            'score' => $validated['score'] ?? 0,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($validated['question_type'] === 'multiple_choice') {
            $this->saveOptions($finalExamQuestion, $validated['options'], $validated['correct_option']);
        } else {
            $finalExamQuestion->options()->delete();
        }

        return redirect()
            ->route('admin.course-management.final-exams.questions.index', $finalExamQuestion->finalExam)
            ->with('success', 'Final exam question has been updated successfully.');
    }

    public function destroy(FinalExamQuestion $finalExamQuestion)
    {
        $finalExam = $finalExamQuestion->finalExam;

        $finalExamQuestion->delete();

        return redirect()
            ->route('admin.course-management.final-exams.questions.index', $finalExam)
            ->with('success', 'Final exam question has been deleted successfully.');
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
