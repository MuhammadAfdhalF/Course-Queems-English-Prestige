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

        $finalExams = $courseLevel->finalExams()
            ->withCount([
                'questions',
                'questions as active_questions_count' => function ($query) {
                    $query->where('is_active', true);
                },
                'attempts',
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('pages.admin.course-management.final-exams.index', compact(
            'courseLevel',
            'finalExams'
        ));
    }

    public function create(CourseLevel $courseLevel)
    {
        $courseLevel->load('courseProgram');

        return view('pages.admin.course-management.final-exams.create', compact('courseLevel'));
    }

    public function store(Request $request, CourseLevel $courseLevel)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'passing_grade' => ['required', 'integer', 'min:0', 'max:100'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['course_level_id'] = $courseLevel->id;
        $requestedActive = $request->boolean('is_active');

        // Safe Section Creation Lifecycle:
        // A newly created section has 0 active questions initially.
        // If requested is_active = true, force is_active = false and set warning message.
        if ($requestedActive) {
            $validated['is_active'] = false;
            FinalExam::create($validated);

            return redirect()
                ->route('admin.course-management.levels.final-exam.index', $courseLevel)
                ->with('warning', 'Final Exam section created as inactive. Add at least one active question before activating it.');
        }

        $validated['is_active'] = false;
        FinalExam::create($validated);

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
            'passing_grade' => ['required', 'integer', 'min:0', 'max:100'],
            'grading_method' => ['required', 'in:auto,manual,mixed'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $requestedActive = $request->boolean('is_active');

        if ($requestedActive) {
            $activeQuestionsCount = $finalExam->questions()->where('is_active', true)->count();
            if ($activeQuestionsCount === 0) {
                $validated['is_active'] = false;
                $finalExam->update($validated);

                return redirect()
                    ->route('admin.course-management.levels.final-exam.index', $finalExam->courseLevel)
                    ->with('warning', 'Final Exam section updated as inactive because it has no active questions. Add at least one active question before activating it.');
            }
        }

        $validated['is_active'] = $requestedActive;
        $finalExam->update($validated);

        return redirect()
            ->route('admin.course-management.levels.final-exam.index', $finalExam->courseLevel)
            ->with('success', 'Final Exam section has been updated successfully.');
    }

    public function toggleActive(FinalExam $finalExam)
    {
        if (! $finalExam->is_active) {
            $activeQuestionsCount = $finalExam->questions()->where('is_active', true)->count();
            if ($activeQuestionsCount === 0) {
                return redirect()
                    ->back()
                    ->with('error', 'This Final Exam section cannot be activated because it has no active questions.');
            }

            $finalExam->update(['is_active' => true]);

            return redirect()
                ->back()
                ->with('success', 'Final Exam section activated successfully.');
        }

        $finalExam->update(['is_active' => false]);

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
