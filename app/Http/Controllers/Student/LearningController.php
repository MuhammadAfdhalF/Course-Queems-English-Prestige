<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\StudentCourseEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LearningController extends Controller
{
    public function show(StudentCourseEnrollment $enrollment): View|RedirectResponse
    {
        $this->authorizeEnrollment($enrollment);

        $enrollment->load([
            'courseLevel.courseProgram',
            'courseLevel.modules' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->with([
                        'materials' => function ($materialQuery) {
                            $materialQuery
                                ->where('is_active', true)
                                ->orderBy('sort_order')
                                ->orderBy('title');
                        },
                        'practices' => function ($practiceQuery) {
                            $practiceQuery
                                ->where('is_active', true);
                        },
                    ])
                    ->orderBy('sort_order')
                    ->orderBy('title');
            },
            'courseLevel.finalExams' => function ($query) {
                $query->where('is_active', true);
            },
        ]);

        $courseLevel = $enrollment->courseLevel;
        $modulesCollection = $courseLevel?->modules ?? collect();
        $finalExam = $courseLevel?->finalExams?->first();

        $modules = $modulesCollection
            ->values()
            ->map(function (Module $module, int $index) use ($enrollment) {
                return [
                    'number' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'title' => $module->title,
                    'status' => 'current',
                    'buttonText' => $index === 0 ? 'Start' : 'Open',
                    'note' => $module->short_description,
                    'href' => route('student.module-material', [
                        'enrollment' => $enrollment,
                        'module' => $module,
                    ]),
                    'materialsCount' => $module->materials->count(),
                    'practicesCount' => $module->practices->count(),
                ];
            })
            ->all();

        return view('pages.student.learning-path', [
            'enrollment' => $enrollment,
            'courseLevel' => $courseLevel,
            'modules' => $modules,
            'modulesCount' => count($modules),
            'finalExam' => $finalExam,
            'continueHref' => $modules[0]['href'] ?? '#',
        ]);
    }

    public function module(StudentCourseEnrollment $enrollment, Module $module): View|RedirectResponse
    {
        $this->authorizeEnrollment($enrollment);

        $enrollment->load('courseLevel.courseProgram');

        abort_unless(
            $module->course_level_id === $enrollment->course_level_id,
            404
        );

        abort_unless($module->is_active, 404);

        $module->load([
            'materials' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('title');
            },
            'practices' => function ($query) {
                $query->where('is_active', true);
            },
        ]);

        return view('pages.student.module-material', [
            'enrollment' => $enrollment,
            'courseLevel' => $enrollment->courseLevel,
            'module' => $module,
            'materials' => $module->materials,
            'practices' => $module->practices,
        ]);
    }

    private function authorizeEnrollment(StudentCourseEnrollment $enrollment): void
    {
        abort_unless($enrollment->student_id === auth()->id(), 403);

        if (! in_array($enrollment->status, ['active', 'completed'], true)) {
            redirect()
                ->route('student.my-courses')
                ->with('error', 'This course is not available for learning access.')
                ->send();
        }
    }
}
