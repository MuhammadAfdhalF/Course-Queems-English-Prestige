<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use App\Models\FinalExam;
use Illuminate\Http\Request;

class FinalExamController extends Controller
{
    public function index(CourseLevel $courseLevel)
    {
        $courseLevel->load('courseProgram');

        $finalExam = $courseLevel->finalExams()
            ->withCount('questions')
            ->first();

        return view('pages.admin.course-management.final-exams.index', compact(
            'courseLevel',
            'finalExam'
        ));
    }

    public function create(CourseLevel $courseLevel)
    {
        $courseLevel->load('courseProgram');

        if ($courseLevel->finalExams()->exists()) {
            return redirect()
                ->route('admin.course-management.levels.final-exam.index', $courseLevel)
                ->with('info', 'This course level already has a final exam.');
        }

        return view('pages.admin.course-management.final-exams.create', compact('courseLevel'));
    }

    public function store(Request $request, CourseLevel $courseLevel)
    {
        if ($courseLevel->finalExams()->exists()) {
            return redirect()
                ->route('admin.course-management.levels.final-exam.index', $courseLevel)
                ->with('info', 'This course level already has a final exam.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'passing_grade' => ['required', 'integer', 'min:0', 'max:100'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['course_level_id'] = $courseLevel->id;
        $validated['is_active'] = $request->boolean('is_active');

        FinalExam::create($validated);

        return redirect()
            ->route('admin.course-management.levels.final-exam.index', $courseLevel)
            ->with('success', 'Final exam has been created successfully.');
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
            'passing_grade' => ['required', 'integer', 'min:0', 'max:100'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $finalExam->update($validated);

        return redirect()
            ->route('admin.course-management.levels.final-exam.index', $finalExam->courseLevel)
            ->with('success', 'Final exam has been updated successfully.');
    }
}
