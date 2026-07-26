<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseProgram;
use App\Models\ModulePractice;
use App\Models\ModulePracticeQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModulePracticeQuestionController extends Controller
{
    public function index(ModulePractice $modulePractice)
    {
        $modulePractice->load('module.courseLevel.courseProgram');

        $questions = $modulePractice->questions()
            ->with('options')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('pages.admin.course-management.practice-questions.index', compact(
            'modulePractice',
            'questions'
        ));
    }

    public function create(ModulePractice $modulePractice)
    {
        $modulePractice->load('module.courseLevel.courseProgram');

        $nextSortOrder = ((int) $modulePractice->questions()->max('sort_order')) + 1;

        return view('pages.admin.course-management.practice-questions.create', compact(
            'modulePractice',
            'nextSortOrder'
        ));
    }

    // ==========================================
    // SCOPED BUILDER AJAX ENDPOINTS (PHASE D)
    // ==========================================

    public function builderStore(Request $request, CourseProgram $courseProgram, ModulePractice $modulePractice)
    {
        $modulePractice->load('module.courseLevel');

        if ($modulePractice->module?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: practice does not belong to this course program.'
            ], 403);
        }

        $validated = $this->validateQuestion($request);

        $question = DB::transaction(function () use ($modulePractice, $validated, $request) {
            $sortOrder = $validated['sort_order'] ?? (((int) $modulePractice->questions()->max('sort_order')) + 1);

            $q = $modulePractice->questions()->create([
                'question_type' => $validated['question_type'],
                'question' => $validated['question'],
                'explanation' => $validated['explanation'] ?? null,
                'score' => $validated['score'] ?? 0,
                'sort_order' => $sortOrder,
                'is_active' => $request->boolean('is_active'),
            ]);

            if ($validated['question_type'] === 'multiple_choice') {
                $this->saveOptions($q, $validated['options'], $validated['correct_option']);
            }

            return $q;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Practice question created successfully.',
            'question_id' => $question->id,
            'redirect_node' => [
                'level' => $modulePractice->module->course_level_id,
                'module' => $modulePractice->module_id,
                'exam' => null,
                'tab' => 'practice'
            ]
        ]);
    }

    public function builderEdit(Request $request, CourseProgram $courseProgram, ModulePracticeQuestion $modulePracticeQuestion)
    {
        $modulePracticeQuestion->load('practice.module.courseLevel', 'options');

        if ($modulePracticeQuestion->practice?->module?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: question does not belong to this course program.'
            ], 403);
        }

        $optionsDict = [];
        $correctOption = null;
        foreach ($modulePracticeQuestion->options as $opt) {
            $optionsDict[$opt->option_label] = $opt->option_text;
            if ($opt->is_correct) {
                $correctOption = $opt->option_label;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $modulePracticeQuestion->id,
                'module_practice_id' => $modulePracticeQuestion->module_practice_id,
                'question_type' => $modulePracticeQuestion->question_type,
                'question' => $modulePracticeQuestion->question,
                'explanation' => $modulePracticeQuestion->explanation,
                'score' => $modulePracticeQuestion->score,
                'sort_order' => $modulePracticeQuestion->sort_order,
                'is_active' => (bool) $modulePracticeQuestion->is_active,
                'options' => $optionsDict,
                'correct_option' => $correctOption,
                'update_url' => route('admin.course-management.programs.builder.questions.update', ['courseProgram' => $courseProgram->id, 'modulePracticeQuestion' => $modulePracticeQuestion->id]),
            ]
        ]);
    }

    public function builderUpdate(Request $request, CourseProgram $courseProgram, ModulePracticeQuestion $modulePracticeQuestion)
    {
        $modulePracticeQuestion->load('practice.module.courseLevel');

        if ($modulePracticeQuestion->practice?->module?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: question does not belong to this course program.'
            ], 403);
        }

        $validated = $this->validateQuestion($request);

        DB::transaction(function () use ($modulePracticeQuestion, $validated, $request) {
            $modulePracticeQuestion->update([
                'question_type' => $validated['question_type'],
                'question' => $validated['question'],
                'explanation' => $validated['explanation'] ?? null,
                'score' => $validated['score'] ?? 0,
                'sort_order' => $validated['sort_order'] ?? $modulePracticeQuestion->sort_order,
                'is_active' => $request->boolean('is_active'),
            ]);

            if ($validated['question_type'] === 'multiple_choice') {
                $this->saveOptions($modulePracticeQuestion, $validated['options'], $validated['correct_option']);
            } else {
                $modulePracticeQuestion->options()->delete();
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Practice question updated successfully.',
            'question_id' => $modulePracticeQuestion->id,
            'redirect_node' => [
                'level' => $modulePracticeQuestion->practice->module->course_level_id,
                'module' => $modulePracticeQuestion->practice->module_id,
                'exam' => null,
                'tab' => 'practice'
            ]
        ]);
    }

    public function builderDestroy(Request $request, CourseProgram $courseProgram, ModulePracticeQuestion $modulePracticeQuestion)
    {
        $modulePracticeQuestion->load('practice.module.courseLevel');

        if ($modulePracticeQuestion->practice?->module?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: question does not belong to this course program.'
            ], 403);
        }

        // Delete Guard: Check if student answers exist
        if ($modulePracticeQuestion->answers()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete question because student answers exist.'
            ], 422);
        }

        $practice = $modulePracticeQuestion->practice;
        $modulePracticeQuestion->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Practice question deleted successfully.',
            'redirect_node' => [
                'level' => $practice->module->course_level_id,
                'module' => $practice->module_id,
                'exam' => null,
                'tab' => 'practice'
            ]
        ]);
    }

    public function builderReorder(Request $request, CourseProgram $courseProgram, ModulePractice $modulePractice)
    {
        $modulePractice->load('module.courseLevel');

        if ($modulePractice->module?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: practice does not belong to this course program.'
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
            return DB::transaction(function () use ($modulePractice, $orderedIds, $originalOrderedIds) {
                // 1. Explicitly execute parent SQL query lock
                $lockedPractice = ModulePractice::whereKey($modulePractice->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 2. Lock & read current server siblings
                $siblings = $lockedPractice->questions()
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
                    ModulePracticeQuestion::where('id', $id)->update(['sort_order' => $index + 1]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Practice questions order updated successfully.'
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            if ($this->isConcurrencyException($e)) {
                return response()->json([
                    'status' => 'conflict',
                    'message' => 'A database concurrency conflict occurred. Reload the latest order and try again.'
                ], 409);
            }
            \Illuminate\Support\Facades\Log::error('Practice question reorder query error: ' . $e->getMessage(), ['exception' => $e]);
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

    public function store(Request $request, ModulePractice $modulePractice)
    {
        $validated = $this->validateQuestion($request);

        $question = $modulePractice->questions()->create([
            'question_type' => $validated['question_type'],
            'question' => $validated['question'],
            'explanation' => $validated['explanation'] ?? null,
            'score' => $validated['score'] ?? 0,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($validated['question_type'] === 'multiple_choice') {
            $this->saveOptions($question, $validated['options'], $validated['correct_option']);
        }

        return redirect()
            ->route('admin.course-management.practices.questions.index', $modulePractice)
            ->with('success', 'Practice question has been created successfully.');
    }

    public function edit(ModulePracticeQuestion $modulePracticeQuestion)
    {
        $modulePracticeQuestion->load('practice.module.courseLevel.courseProgram', 'options');

        return view('pages.admin.course-management.practice-questions.edit', compact('modulePracticeQuestion'));
    }

    public function update(Request $request, ModulePracticeQuestion $modulePracticeQuestion)
    {
        $validated = $this->validateQuestion($request);

        $modulePracticeQuestion->update([
            'question_type' => $validated['question_type'],
            'question' => $validated['question'],
            'explanation' => $validated['explanation'] ?? null,
            'score' => $validated['score'] ?? 0,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($validated['question_type'] === 'multiple_choice') {
            $this->saveOptions($modulePracticeQuestion, $validated['options'], $validated['correct_option']);
        } else {
            $modulePracticeQuestion->options()->delete();
        }

        return redirect()
            ->route('admin.course-management.practices.questions.index', $modulePracticeQuestion->practice)
            ->with('success', 'Practice question has been updated successfully.');
    }

    public function destroy(ModulePracticeQuestion $modulePracticeQuestion)
    {
        $practice = $modulePracticeQuestion->practice;

        $modulePracticeQuestion->delete();

        return redirect()
            ->route('admin.course-management.practices.questions.index', $practice)
            ->with('success', 'Practice question has been deleted successfully.');
    }

    private function validateQuestion(Request $request): array
    {
        $validated = $request->validate([
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

        return $validated;
    }

    private function saveOptions(ModulePracticeQuestion $question, array $options, string $correctOption): void
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

    public function preview(ModulePractice $modulePractice)
    {
        $modulePractice->load('module.courseLevel.courseProgram');

        $questions = $modulePractice->questions()
            ->with('options')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('pages.admin.course-management.practice-questions.preview', compact(
            'modulePractice',
            'questions'
        ));
    }
}
