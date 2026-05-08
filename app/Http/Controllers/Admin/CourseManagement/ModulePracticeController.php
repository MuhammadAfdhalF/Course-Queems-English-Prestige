<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModulePractice;
use Illuminate\Http\Request;

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
