<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\FinalExam;
use App\Models\Module;
use Illuminate\Http\Request;

class CourseBuilderController extends Controller
{
    /**
     * Display the Course Builder Shell (Phase A - Read-Only Navigation).
     */
    public function builder(CourseProgram $courseProgram)
    {
        $courseProgram->load([
            'courseLevels' => function ($q) {
                $q->orderBy('sort_order')->orderBy('id')
                  ->withCount(['modules', 'finalExams'])
                  ->with([
                      'modules' => function ($mq) {
                          $mq->orderBy('sort_order')->orderBy('id')
                             ->withCount('materials')
                             ->with([
                                 'practices' => function ($pq) {
                                     $pq->withCount('questions');
                                 }
                             ]);
                      },
                      'finalExams' => function ($fq) {
                          $fq->orderBy('sort_order')->orderBy('id')
                             ->withCount('questions');
                      }
                  ]);
            }
        ]);

        $firstLevelId = $courseProgram->courseLevels->first()?->id;

        $totalLevels = $courseProgram->courseLevels->count();
        $totalModules = $courseProgram->courseLevels->sum(fn($l) => $l->modules->count());
        $totalMaterials = $courseProgram->courseLevels->sum(fn($l) => $l->modules->sum('materials_count'));
        $totalPractices = $courseProgram->courseLevels->sum(fn($l) => $l->modules->sum(fn($m) => $m->practices->count()));
        $totalFinalExams = $courseProgram->courseLevels->sum(fn($l) => $l->finalExams->count());

        $initialWorkspaceHtml = view('partials.admin.course-management.builder.workspace.program', [
            'courseProgram' => $courseProgram,
            'totalLevels' => $totalLevels,
            'totalModules' => $totalModules,
            'totalMaterials' => $totalMaterials,
            'totalPractices' => $totalPractices,
            'totalFinalExams' => $totalFinalExams,
        ])->render();

        return view('pages.admin.course-management.builder.index', compact(
            'courseProgram',
            'firstLevelId',
            'initialWorkspaceHtml'
        ));
    }

    /**
     * Render the Tree HTML Partial for AJAX refresh.
     */
    public function tree(CourseProgram $courseProgram)
    {
        $courseProgram->load([
            'courseLevels' => function ($q) {
                $q->orderBy('sort_order')->orderBy('id')
                  ->with([
                      'modules' => function ($mq) {
                          $mq->orderBy('sort_order')->orderBy('id')
                             ->withCount('materials')
                             ->with([
                                 'practices' => function ($pq) {
                                     $pq->withCount('questions');
                                 }
                             ]);
                      },
                      'finalExams' => function ($fq) {
                          $fq->orderBy('sort_order')->orderBy('id')
                             ->withCount('questions');
                      }
                  ]);
            }
        ]);

        $html = view('partials.admin.course-management.builder.tree', compact('courseProgram'))->render();

        return response()->json([
            'status' => 'success',
            'html' => $html,
            'first_level_id' => $courseProgram->courseLevels->first()?->id,
        ]);
    }

    /**
     * Render the Workspace HTML Partial based on query parameters.
     */
    public function workspace(Request $request, CourseProgram $courseProgram)
    {
        $levelId = $request->query('level');
        $moduleId = $request->query('module');
        $examId = $request->query('exam');
        $tab = $request->query('tab');

        // Validation & Ownership Guards
        $selectedLevel = null;
        $selectedModule = null;
        $selectedExam = null;

        if ($levelId) {
            $selectedLevel = CourseLevel::find($levelId);

            if (!$selectedLevel || $selectedLevel->course_program_id !== $courseProgram->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Level not found or does not belong to this program.'
                ], 404);
            }
        }

        if ($moduleId) {
            $selectedModule = Module::with(['practices' => fn($q) => $q->withCount('questions'), 'materials' => fn($mq) => $mq->orderBy('sort_order')->orderBy('id')])
                ->withCount('materials')
                ->find($moduleId);

            if (!$selectedModule || !$selectedModule->courseLevel || $selectedModule->courseLevel->course_program_id !== $courseProgram->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Module not found or does not belong to this program.'
                ], 404);
            }

            if ($selectedLevel && $selectedModule->course_level_id !== $selectedLevel->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Module does not belong to the selected level.'
                ], 404);
            }

            $selectedLevel = $selectedModule->courseLevel;
        }

        if ($examId) {
            $selectedExam = FinalExam::withCount('questions')->find($examId);

            if (!$selectedExam || !$selectedExam->courseLevel || $selectedExam->courseLevel->course_program_id !== $courseProgram->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Final Exam Section not found or does not belong to this program.'
                ], 404);
            }

            if ($selectedLevel && $selectedExam->course_level_id !== $selectedLevel->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Final Exam Section does not belong to the selected level.'
                ], 404);
            }

            $selectedLevel = $selectedExam->courseLevel;
        }

        // Context determination & HTML rendering
        if ($selectedModule && $tab === 'practice') {
            $practice = $selectedModule->practices->first();
            $html = view('partials.admin.course-management.builder.workspace.practice', [
                'courseProgram' => $courseProgram,
                'level' => $selectedLevel,
                'module' => $selectedModule,
                'practice' => $practice,
            ])->render();
        } elseif ($selectedModule) {
            $materialsPaginator = null;
            if ($tab === 'materials') {
                $page = max(1, (int)$request->query('page', 1));
                $materialsPaginator = $selectedModule->materials()
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->paginate(15, ['*'], 'page', $page);
            }

            $html = view('partials.admin.course-management.builder.workspace.module', [
                'courseProgram' => $courseProgram,
                'level' => $selectedLevel,
                'module' => $selectedModule,
                'selectedModule' => $selectedModule,
                'tab' => $tab ?: 'overview',
                'materialsPaginator' => $materialsPaginator,
            ])->render();
        } elseif ($selectedExam) {
            $html = view('partials.admin.course-management.builder.workspace.final-exam-section', [
                'courseProgram' => $courseProgram,
                'level' => $selectedLevel,
                'exam' => $selectedExam,
                'tab' => $tab ?: 'overview',
            ])->render();
        } elseif ($selectedLevel && $tab === 'final-exam') {
            $selectedLevel->load(['finalExams' => fn($q) => $q->orderBy('sort_order')->orderBy('id')->withCount('questions')]);

            $html = view('partials.admin.course-management.builder.workspace.final-exam-folder', [
                'courseProgram' => $courseProgram,
                'level' => $selectedLevel,
            ])->render();
        } elseif ($selectedLevel) {
            $selectedLevel->load([
                'modules' => fn($q) => $q->orderBy('sort_order')->orderBy('id')->withCount('materials')->with(['practices' => fn($pq) => $pq->withCount('questions')]),
                'finalExams' => fn($q) => $q->orderBy('sort_order')->orderBy('id')->withCount('questions'),
            ]);

            $html = view('partials.admin.course-management.builder.workspace.level', [
                'courseProgram' => $courseProgram,
                'level' => $selectedLevel,
                'tab' => $tab ?: 'overview',
            ])->render();
        } else {
            $courseProgram->load([
                'courseLevels' => function ($q) {
                    $q->orderBy('sort_order')->orderBy('id')
                      ->withCount(['modules', 'finalExams'])
                      ->with([
                          'modules' => fn($mq) => $mq->withCount('materials')->with(['practices' => fn($pq) => $pq->withCount('questions')]),
                          'finalExams' => fn($fq) => $fq->withCount('questions')
                      ]);
                }
            ]);

            $totalLevels = $courseProgram->courseLevels->count();
            $totalModules = $courseProgram->courseLevels->sum(fn($l) => $l->modules->count());
            $totalMaterials = $courseProgram->courseLevels->sum(fn($l) => $l->modules->sum('materials_count'));
            $totalPractices = $courseProgram->courseLevels->sum(fn($l) => $l->modules->sum(fn($m) => $m->practices->count()));
            $totalFinalExams = $courseProgram->courseLevels->sum(fn($l) => $l->finalExams->count());

            $html = view('partials.admin.course-management.builder.workspace.program', [
                'courseProgram' => $courseProgram,
                'totalLevels' => $totalLevels,
                'totalModules' => $totalModules,
                'totalMaterials' => $totalMaterials,
                'totalPractices' => $totalPractices,
                'totalFinalExams' => $totalFinalExams,
            ])->render();
        }

        return response()->json([
            'status' => 'success',
            'html' => $html,
            'node' => [
                'level_id' => $selectedLevel?->id,
                'module_id' => $selectedModule?->id,
                'exam_id' => $selectedExam?->id,
                'tab' => $tab ?: 'overview',
            ]
        ]);
    }
}
