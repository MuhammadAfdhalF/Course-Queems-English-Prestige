<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\FinalExam;
use App\Services\AssessmentConfigService;
use Illuminate\Http\Request;

class FinalExamController extends Controller
{
    public function __construct(
        protected AssessmentConfigService $configService
    ) {}

    public function index(CourseLevel $courseLevel)
    {
        $courseLevel->load('courseProgram');

        $finalExams = $courseLevel->finalExams()
            ->withCount([
                'questions',
                'questions as active_questions_count' => function ($query) {
                    $query->where('is_active', true);
                },
                'attempts',
            ])
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('pages.admin.course-management.final-exams.index', compact(
            'courseLevel',
            'finalExams'
        ));
    }

    public function create(CourseLevel $courseLevel)
    {
        $courseLevel->load('courseProgram');
        $nextSortOrder = ((int) $courseLevel->finalExams()->max('sort_order')) + 1;

        return view('pages.admin.course-management.final-exams.create', compact(
            'courseLevel',
            'nextSortOrder'
        ));
    }

    // ==========================================
    // SCOPED BUILDER AJAX ENDPOINTS (PHASE E)
    // ==========================================

    public function builderStore(Request $request, CourseProgram $courseProgram, CourseLevel $courseLevel)
    {
        if ($courseLevel->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: course level does not belong to this program.'
            ], 403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $config = $this->configService->validateAndNormalize($request, null, true);

        $data = array_merge($validated, $config);
        $data['course_level_id'] = $courseLevel->id;
        $data['sort_order'] = $data['sort_order'] ?? (((int) $courseLevel->finalExams()->max('sort_order')) + 1);
        $data['is_active'] = false; // Initial section has 0 active questions

        $finalExam = FinalExam::create($data);

        $requestedActive = $request->boolean('is_active');
        $msg = $requestedActive
            ? 'Final Exam section created as inactive (0 active questions). Add active questions, then activate it.'
            : 'Final Exam section created successfully.';

        return response()->json([
            'status' => 'success',
            'message' => $msg,
            'section_id' => $finalExam->id,
            'redirect_node' => [
                'level' => $courseLevel->id,
                'module' => null,
                'exam' => $finalExam->id,
                'tab' => 'overview'
            ]
        ]);
    }

    public function builderEdit(Request $request, CourseProgram $courseProgram, FinalExam $finalExam)
    {
        $finalExam->load('courseLevel');

        if ($finalExam->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: exam section does not belong to this program.'
            ], 403);
        }

        $resultModeVal = $finalExam->result_mode?->value ?? (string) $finalExam->result_mode ?? 'pass_fail';
        $maxAttempts = $finalExam->max_attempts;
        $attemptMode = $maxAttempts === 1 ? 'one' : ($maxAttempts > 1 ? 'multiple' : 'unlimited');
        $hasAttempts = $finalExam->attempts()->exists();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $finalExam->id,
                'course_level_id' => $finalExam->course_level_id,
                'title' => $finalExam->title,
                'description' => $finalExam->description,
                'total_score' => (float) $finalExam->total_score,
                'result_mode' => $resultModeVal,
                'passing_score' => $finalExam->passing_score !== null ? (float) $finalExam->passing_score : null,
                'passing_grade' => $finalExam->passing_grade,
                'grading_method' => $finalExam->grading_method,
                'attempt_mode' => $attemptMode,
                'max_attempts' => $maxAttempts,
                'sort_order' => $finalExam->sort_order,
                'is_active' => (bool) $finalExam->is_active,
                'is_locked' => $hasAttempts,
                'update_url' => route('admin.course-management.programs.builder.final-exams.update', ['courseProgram' => $courseProgram->id, 'finalExam' => $finalExam->id]),
            ]
        ]);
    }

    public function builderUpdate(Request $request, CourseProgram $courseProgram, FinalExam $finalExam)
    {
        $finalExam->load('courseLevel');

        if ($finalExam->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: exam section does not belong to this program.'
            ], 403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $config = $this->configService->validateAndNormalize($request, $finalExam, true);

        $data = array_merge($validated, $config);
        $data['sort_order'] = $data['sort_order'] ?? $finalExam->sort_order;

        $requestedActive = $request->boolean('is_active');
        $deactivatedByConfig = false;

        $data['is_active'] = $this->configService->resolveIsActiveStatus($request, $finalExam, $config);
        if ($requestedActive && !$data['is_active']) {
            $deactivatedByConfig = true;
        }

        if ($data['is_active']) {
            $activeQuestionsCount = $finalExam->questions()->where('is_active', true)->count();
            if ($activeQuestionsCount === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This Final Exam section cannot be activated because it has no active questions. Add at least one active question first.',
                    'errors' => [
                        'is_active' => ['This Final Exam section cannot be activated because it has no active questions.']
                    ]
                ], 422);
            }
        }

        $finalExam->update($data);

        $msg = $deactivatedByConfig
            ? 'Final Exam section updated, but was deactivated because its scoring configuration changed.'
            : 'Final Exam section updated successfully.';

        return response()->json([
            'status' => 'success',
            'message' => $msg,
            'section_id' => $finalExam->id,
            'redirect_node' => [
                'level' => $finalExam->course_level_id,
                'module' => null,
                'exam' => $finalExam->id,
                'tab' => 'overview'
            ]
        ]);
    }

    public function builderToggleActive(Request $request, CourseProgram $courseProgram, FinalExam $finalExam)
    {
        $finalExam->load('courseLevel');

        if ($finalExam->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: exam section does not belong to this program.'
            ], 403);
        }

        if (! $finalExam->is_active) {
            $activeQuestionsCount = $finalExam->questions()->where('is_active', true)->count();
            if ($activeQuestionsCount === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This Final Exam section cannot be activated because it has no active questions. Add at least one active question first.'
                ], 422);
            }

            $finalExam->update(['is_active' => true]);

            return response()->json([
                'status' => 'success',
                'message' => 'Final Exam section activated successfully.',
                'is_active' => true
            ]);
        }

        $finalExam->update(['is_active' => false]);

        return response()->json([
            'status' => 'success',
            'message' => 'Final Exam section deactivated successfully.',
            'is_active' => false
        ]);
    }

    public function builderDestroy(Request $request, CourseProgram $courseProgram, FinalExam $finalExam)
    {
        $finalExam->load('courseLevel');

        if ($finalExam->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: exam section does not belong to this program.'
            ], 403);
        }

        // Delete Guard: Check if student attempts exist
        if ($finalExam->attempts()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'This Final Exam section already has student attempts and cannot be deleted. Deactivate it instead.'
            ], 422);
        }

        $levelId = $finalExam->course_level_id;
        $finalExam->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Final Exam section deleted successfully.',
            'redirect_node' => [
                'level' => $levelId,
                'module' => null,
                'exam' => null,
                'tab' => 'final-exam'
            ]
        ]);
    }

    public function builderReorder(Request $request, CourseProgram $courseProgram, CourseLevel $courseLevel)
    {
        if ($courseLevel->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: course level does not belong to this program.'
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
            return \Illuminate\Support\Facades\DB::transaction(function () use ($courseLevel, $orderedIds, $originalOrderedIds) {
                // 1. Explicitly execute parent SQL query lock
                $lockedLevel = CourseLevel::whereKey($courseLevel->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 2. Lock & read current server siblings
                $siblings = $lockedLevel->finalExams()
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
                    FinalExam::where('id', $id)->update(['sort_order' => $index + 1]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Final Exam sections order updated successfully.'
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            if ($this->isConcurrencyException($e)) {
                return response()->json([
                    'status' => 'conflict',
                    'message' => 'A database concurrency conflict occurred. Reload the latest order and try again.'
                ], 409);
            }
            \Illuminate\Support\Facades\Log::error('Final Exam section reorder query error: ' . $e->getMessage(), ['exception' => $e]);
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

    public function store(Request $request, CourseLevel $courseLevel)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $config = $this->configService->validateAndNormalize($request, null, true);

        $data = array_merge($validated, $config);
        $data['course_level_id'] = $courseLevel->id;
        $requestedActive = $request->boolean('is_active');
        $data['is_active'] = false;

        $finalExam = FinalExam::create($data);

        if ($requestedActive) {
            return redirect()
                ->route('admin.course-management.final-exams.questions.create', [
                    'finalExam' => $finalExam,
                    'activate_when_ready' => 1,
                ])
                ->with('info', 'Final Exam section created. Add the first active question to activate it automatically.');
        }

        return redirect()
            ->route('admin.course-management.levels.final-exam.index', $courseLevel)
            ->with('success', 'Final Exam section has been created successfully.');
    }

    public function edit(FinalExam $finalExam)
    {
        $finalExam->load('courseLevel.courseProgram');

        return view('pages.admin.course-management.final-exams.edit', compact('finalExam'));
    }

    public function update(Request $request, FinalExam $finalExam)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $config = $this->configService->validateAndNormalize($request, $finalExam, true);

        $data = array_merge($validated, $config);
        $requestedActive = $request->boolean('is_active');
        $deactivatedByConfig = false;

        if ($requestedActive) {
            $activeQuestionsCount = $finalExam->questions()->where('is_active', true)->count();
            if ($activeQuestionsCount === 0) {
                $data['is_active'] = false;
                $finalExam->update($data);

                return redirect()
                    ->route('admin.course-management.levels.final-exam.index', $finalExam->courseLevel)
                    ->with('warning', 'Final Exam section updated as inactive because it has no active questions. Add at least one active question before activating it.');
            }
        }

        $data['is_active'] = $this->configService->resolveIsActiveStatus($request, $finalExam, $config);
        if ($requestedActive && !$data['is_active']) {
            $deactivatedByConfig = true;
        }

        $finalExam->update($data);

        $msg = $deactivatedByConfig
            ? 'Final Exam section updated, but was deactivated because its scoring configuration changed.'
            : 'Final Exam section has been updated successfully.';

        return redirect()
            ->route('admin.course-management.levels.final-exam.index', $finalExam->courseLevel)
            ->with($deactivatedByConfig ? 'warning' : 'success', $msg);
    }

    public function toggleActive(FinalExam $finalExam)
    {
        if (! $finalExam->is_active) {
            $activeQuestionsCount = $finalExam->questions()->where('is_active', true)->count();
            if ($activeQuestionsCount === 0) {
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'This Final Exam section cannot be activated because it has no active questions. Add at least one active question first.'
                    ], 422);
                }
                return redirect()
                    ->back()
                    ->with('error', 'This Final Exam section cannot be activated because it has no active questions.');
            }

            $finalExam->update(['is_active' => true]);

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Final Exam section activated successfully.',
                    'is_active' => true
                ]);
            }

            return redirect()
                ->back()
                ->with('success', 'Final Exam section activated successfully.');
        }

        $finalExam->update(['is_active' => false]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Final Exam section deactivated successfully.',
                'is_active' => false
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Final Exam section deactivated successfully.');
    }

    public function destroy(FinalExam $finalExam)
    {
        $attemptsCount = $finalExam->attempts()->count();

        if ($attemptsCount > 0) {
            return redirect()
                ->back()
                ->with('error', 'This Final Exam section already has student attempts and cannot be deleted. Deactivate it instead.');
        }

        $courseLevel = $finalExam->courseLevel;
        $finalExam->delete();

        return redirect()
            ->route('admin.course-management.levels.final-exam.index', $courseLevel)
            ->with('success', 'Final Exam section has been deleted successfully.');
    }
}
