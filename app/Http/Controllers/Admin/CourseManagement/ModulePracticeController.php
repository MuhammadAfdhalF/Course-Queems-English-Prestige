<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseProgram;
use App\Models\Module;
use App\Models\ModulePractice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModulePracticeController extends Controller
{
    public function index(Module $module)
    {
        $module->load('courseLevel.courseProgram');

        $practice = $module->practices()
            ->withCount('questions')
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
                ->with('info', 'This module already has a practice.');
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
            'passing_grade' => ['required', 'integer', 'min:0', 'max:100'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['module_id'] = $module->id;
        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active');

        $practice = ModulePractice::create($validated);

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

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $modulePractice->id,
                'module_id' => $modulePractice->module_id,
                'title' => $modulePractice->title,
                'description' => $modulePractice->description,
                'passing_grade' => $modulePractice->passing_grade,
                'grading_method' => $modulePractice->grading_method,
                'max_attempts' => $modulePractice->max_attempts,
                'is_required' => (bool) $modulePractice->is_required,
                'is_active' => (bool) $modulePractice->is_active,
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
            'passing_grade' => ['required', 'integer', 'min:0', 'max:100'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active');

        $modulePractice->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Module practice updated successfully.',
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
            'passing_grade' => ['required', 'integer', 'min:0', 'max:100'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['module_id'] = $module->id;
        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active');

        ModulePractice::create($validated);

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
            'passing_grade' => ['required', 'integer', 'min:0', 'max:100'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active');

        $modulePractice->update($validated);

        return redirect()
            ->route('admin.course-management.modules.practice.index', $modulePractice->module)
            ->with('success', 'Module practice has been updated successfully.');
    }
}
