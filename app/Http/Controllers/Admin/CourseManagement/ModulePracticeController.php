<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseProgram;
use App\Models\Module;
use App\Models\ModulePractice;
use App\Services\AssessmentConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModulePracticeController extends Controller
{
    public function __construct(
        protected AssessmentConfigService $configService
    ) {}

    public function index(Module $module)
    {
        $module->load('courseLevel.courseProgram');

        $practice = $module->practices()
            ->withCount([
                'questions',
                'questions as active_questions_count' => function ($query) {
                    $query->where('is_active', true);
                },
                'attempts',
            ])
            ->first();

        return view('pages.admin.course-management.practices.index', compact(
            'module',
            'practice'
        ));
    }

    public function create(Module $module)
    {
        $module->load('courseLevel.courseProgram');

        if ($module->practices()->exists()) {
            return redirect()
                ->route('admin.course-management.modules.practice.index', $module)
                ->with('info', 'This module already has a practice configuration.');
        }

        return view('pages.admin.course-management.practices.create', compact('module'));
    }

    // ==========================================
    // SCOPED BUILDER AJAX ENDPOINTS (PHASE D)
    // ==========================================

    public function builderStore(Request $request, CourseProgram $courseProgram, Module $module)
    {
        $module->load('courseLevel');

        if ($module->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: module does not belong to this course program.'
            ], 403);
        }

        if ($module->practices()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'This module already has a practice configuration.'
            ], 422);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $config = $this->configService->validateAndNormalize($request, null, true);

        $data = array_merge($validated, $config);
        $data['module_id'] = $module->id;
        $data['is_required'] = $request->boolean('is_required');
        $data['is_active'] = $request->boolean('is_active');

        $practice = ModulePractice::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Module practice created successfully.',
            'practice_id' => $practice->id,
            'redirect_node' => [
                'level' => $module->course_level_id,
                'module' => $module->id,
                'exam' => null,
                'tab' => 'practice'
            ]
        ]);
    }

    public function builderEdit(Request $request, CourseProgram $courseProgram, ModulePractice $modulePractice)
    {
        $modulePractice->load('module.courseLevel');

        if ($modulePractice->module?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: practice does not belong to this course program.'
            ], 403);
        }

        $resultModeVal = $modulePractice->result_mode?->value ?? (string) $modulePractice->result_mode ?? 'pass_fail';
        $maxAttempts = $modulePractice->max_attempts;
        $attemptMode = $maxAttempts === 1 ? 'one' : ($maxAttempts > 1 ? 'multiple' : 'unlimited');
        $hasAttempts = $modulePractice->attempts()->exists();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $modulePractice->id,
                'module_id' => $modulePractice->module_id,
                'title' => $modulePractice->title,
                'description' => $modulePractice->description,
                'total_score' => (float) $modulePractice->total_score,
                'result_mode' => $resultModeVal,
                'passing_score' => $modulePractice->passing_score !== null ? (float) $modulePractice->passing_score : null,
                'passing_grade' => $modulePractice->passing_grade,
                'grading_method' => $modulePractice->grading_method,
                'attempt_mode' => $attemptMode,
                'max_attempts' => $maxAttempts,
                'is_required' => (bool) $modulePractice->is_required,
                'is_active' => (bool) $modulePractice->is_active,
                'is_locked' => $hasAttempts,
                'update_url' => route('admin.course-management.programs.builder.practices.update', ['courseProgram' => $courseProgram->id, 'modulePractice' => $modulePractice->id]),
            ]
        ]);
    }

    public function builderUpdate(Request $request, CourseProgram $courseProgram, ModulePractice $modulePractice)
    {
        $modulePractice->load('module.courseLevel');

        if ($modulePractice->module?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: practice does not belong to this course program.'
            ], 403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $config = $this->configService->validateAndNormalize($request, $modulePractice, true);

        $data = array_merge($validated, $config);
        $data['is_required'] = $request->boolean('is_required');

        $requestedActive = $request->boolean('is_active');
        $data['is_active'] = $this->configService->resolveIsActiveStatus($request, $modulePractice, $config);
        $deactivatedByConfig = ($requestedActive && !$data['is_active']);

        $modulePractice->update($data);

        $msg = $deactivatedByConfig
            ? 'Module practice updated, but was deactivated because its scoring configuration changed.'
            : 'Module practice updated successfully.';

        return response()->json([
            'status' => 'success',
            'message' => $msg,
            'practice_id' => $modulePractice->id,
            'redirect_node' => [
                'level' => $modulePractice->module->course_level_id,
                'module' => $modulePractice->module_id,
                'exam' => null,
                'tab' => 'practice'
            ]
        ]);
    }

    public function builderDestroy(Request $request, CourseProgram $courseProgram, ModulePractice $modulePractice)
    {
        $modulePractice->load('module.courseLevel');

        if ($modulePractice->module?->courseLevel?->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: practice does not belong to this course program.'
            ], 403);
        }

        // Delete Guard: Check if student attempts exist
        if ($modulePractice->attempts()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete practice because student attempt records exist.'
            ], 422);
        }

        $module = $modulePractice->module;
        $modulePractice->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Module practice deleted successfully.',
            'redirect_node' => [
                'level' => $module->course_level_id,
                'module' => $module->id,
                'exam' => null,
                'tab' => 'practice'
            ]
        ]);
    }

    // ==========================================
    // LEGACY FULL-PAGE ENDPOINTS
    // ==========================================

    public function store(Request $request, Module $module)
    {
        if ($module->practices()->exists()) {
            return redirect()
                ->route('admin.course-management.modules.practice.index', $module)
                ->with('info', 'This module already has a practice.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $config = $this->configService->validateAndNormalize($request, null, true);

        $data = array_merge($validated, $config);
        $data['module_id'] = $module->id;
        $data['is_required'] = $request->boolean('is_required');
        $data['is_active'] = $request->boolean('is_active');

        ModulePractice::create($data);

        return redirect()
            ->route('admin.course-management.modules.practice.index', $module)
            ->with('success', 'Module practice has been created successfully.');
    }

    public function edit(ModulePractice $modulePractice)
    {
        $modulePractice->load('module.courseLevel.courseProgram');

        return view('pages.admin.course-management.practices.edit', compact('modulePractice'));
    }

    public function update(Request $request, ModulePractice $modulePractice)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $config = $this->configService->validateAndNormalize($request, $modulePractice, true);

        $data = array_merge($validated, $config);
        $data['is_required'] = $request->boolean('is_required');

        $requestedActive = $request->boolean('is_active');
        $data['is_active'] = $this->configService->resolveIsActiveStatus($request, $modulePractice, $config);
        $deactivatedByConfig = ($requestedActive && !$data['is_active']);

        $modulePractice->update($data);

        $msg = $deactivatedByConfig
            ? 'Module practice updated, but was deactivated because its scoring configuration changed.'
            : 'Module practice has been updated successfully.';

        return redirect()
            ->route('admin.course-management.modules.practice.index', $modulePractice->module)
            ->with($deactivatedByConfig ? 'warning' : 'success', $msg);
    }
}
