<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\StudentCourseEnrollment;
use App\Models\StudentModuleProgress;
use App\Services\StudentProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\ModulePracticeAttempt;
use App\Models\FinalExamAttempt;
use App\Models\FinalExam;

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
                            $practiceQuery->where('is_active', true);
                        },
                    ])
                    ->orderBy('sort_order')
                    ->orderBy('title');
            },
            'courseLevel.finalExams' => function ($query) {
                $query->where('is_active', true);
            },
            'moduleProgress',
            'certificate',
        ]);

        $courseLevel = $enrollment->courseLevel;

        abort_unless($courseLevel, 404);

        $modulesCollection = $courseLevel->modules ?? collect();
        $isFinalExamUnlocked = (float) $enrollment->progress_percentage >= 100;
        $isEnrollmentCompleted = $enrollment->status === 'completed' && $enrollment->certificate !== null;

        $activeFinalExams = $courseLevel->finalExams()
            ->where('is_active', true)
            ->withCount(['questions as active_questions_count' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $studentAttemptsGrouped = FinalExamAttempt::query()
            ->where('student_id', auth()->id())
            ->whereIn('final_exam_id', $activeFinalExams->pluck('id'))
            ->whereIn('status', ['passed', 'failed', 'waiting_review', 'in_progress'])
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->get()
            ->groupBy('final_exam_id');

        $finalExamSections = $activeFinalExams->map(function (FinalExam $exam) use (
            $studentAttemptsGrouped,
            $enrollment,
            $isFinalExamUnlocked,
            $isEnrollmentCompleted
        ) {
            $attempts = $studentAttemptsGrouped->get($exam->id, collect());

            $submittedAttempts = $attempts->filter(fn ($a) => in_array($a->status, ['passed', 'failed', 'waiting_review'], true));
            $attemptsUsed = $submittedAttempts->count();

            $passedAttempt = $submittedAttempts->firstWhere('status', 'passed');
            $waitingReviewAttempt = $attempts->firstWhere('status', 'waiting_review');
            $inProgressAttempt = $attempts->firstWhere('status', 'in_progress');
            $latestSubmittedAttempt = $submittedAttempts->first();

            $status = match (true) {
                $passedAttempt !== null => 'passed',
                $waitingReviewAttempt !== null => 'waiting_review',
                $inProgressAttempt !== null => 'in_progress',
                $latestSubmittedAttempt?->status === 'failed' => 'failed',
                default => 'not_started',
            };

            $score = match ($status) {
                'passed' => (float) $passedAttempt->total_score,
                'failed' => (float) $latestSubmittedAttempt->total_score,
                default => null,
            };

            $hasActiveQuestions = $exam->active_questions_count > 0;
            $maxAttempts = $exam->max_attempts ? (int) $exam->max_attempts : null;
            $attemptsRemaining = $maxAttempts !== null ? max(0, $maxAttempts - $attemptsUsed) : null;
            $isAttemptLimitReached = $maxAttempts !== null && $attemptsUsed >= $maxAttempts;

            $isExemptNewSection = $isEnrollmentCompleted && $status === 'not_started';

            $canStart = $isFinalExamUnlocked && $hasActiveQuestions && ! $isExemptNewSection && ! $passedAttempt && ! $waitingReviewAttempt && ! $inProgressAttempt && ! $isAttemptLimitReached;
            $canContinue = $isFinalExamUnlocked && ! $isExemptNewSection && $inProgressAttempt !== null;
            $canRetake = $isFinalExamUnlocked && $hasActiveQuestions && ! $isExemptNewSection && ! $passedAttempt && ! $waitingReviewAttempt && ! $inProgressAttempt && $latestSubmittedAttempt?->status === 'failed' && ! $isAttemptLimitReached;

            $latestAttempt = $passedAttempt ?? $waitingReviewAttempt ?? $inProgressAttempt ?? $latestSubmittedAttempt;

            return [
                'exam' => $exam,
                'id' => $exam->id,
                'title' => $exam->title,
                'description' => $exam->description,
                'passing_grade' => $exam->passing_grade,
                'grading_method' => $exam->grading_method,
                'max_attempts' => $maxAttempts,
                'sort_order' => $exam->sort_order,
                'active_questions_count' => $exam->active_questions_count,
                'has_active_questions' => $hasActiveQuestions,
                'status' => $status,
                'score' => $score,
                'attempts_used' => $attemptsUsed,
                'attempts_remaining' => $attemptsRemaining,
                'is_attempt_limit_reached' => $isAttemptLimitReached,
                'is_exempt_new_section' => $isExemptNewSection,
                'can_start' => $canStart,
                'can_continue' => $canContinue,
                'can_retake' => $canRetake,
                'latest_attempt' => $latestAttempt,
                'passed_attempt' => $passedAttempt,
                'in_progress_attempt' => $inProgressAttempt,
                'waiting_review_attempt' => $waitingReviewAttempt,
                'start_href' => route('student.final-exam', ['enrollment' => $enrollment, 'finalExam' => $exam]),
                'result_href' => $latestAttempt ? route('student.final-exam-result', ['enrollment' => $enrollment, 'attempt' => $latestAttempt]) : null,
            ];
        });

        $finalExam = $activeFinalExams->first();
        $firstSection = $finalExamSections->first();
        $latestFinalExamAttempt = $firstSection['latest_attempt'] ?? null;
        $finalExamAttemptCount = $firstSection['attempts_used'] ?? 0;
        $canRetakeFinalExam = $firstSection['can_retake'] ?? false;

        $completedModuleIds = $enrollment->moduleProgress
            ->where('status', 'completed')
            ->pluck('module_id')
            ->all();

        $firstIncompleteIndex = $modulesCollection
            ->values()
            ->search(fn(Module $module) => ! in_array($module->id, $completedModuleIds, true));

        if ($firstIncompleteIndex === false) {
            $firstIncompleteIndex = $modulesCollection->count();
        }

        $modules = $modulesCollection
            ->values()
            ->map(function (Module $module, int $index) use ($enrollment, $completedModuleIds, $firstIncompleteIndex) {
                $isCompleted = in_array($module->id, $completedModuleIds, true);

                $status = match (true) {
                    $isCompleted => 'completed',
                    $index === $firstIncompleteIndex => 'current',
                    default => 'locked',
                };

                $buttonText = match ($status) {
                    'completed' => 'Review',
                    'current' => $index === 0 ? 'Start' : 'Continue',
                    default => 'Locked',
                };

                return [
                    'number' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'title' => $module->title,
                    'status' => $status,
                    'buttonText' => $buttonText,
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

        $continueModule = collect($modules)->firstWhere('status', 'current')
            ?? collect($modules)->firstWhere('status', 'completed');

        return view('pages.student.learning-path', [
            'enrollment' => $enrollment,
            'courseLevel' => $courseLevel,
            'modules' => $modules,
            'modulesCount' => count($modules),
            'finalExamSections' => $finalExamSections,
            'finalExam' => $finalExam,
            'latestFinalExamAttempt' => $latestFinalExamAttempt,
            'finalExamAttemptCount' => $finalExamAttemptCount,
            'canRetakeFinalExam' => $canRetakeFinalExam,
            'isFinalExamUnlocked' => $isFinalExamUnlocked,
            'continueHref' => $continueModule['href'] ?? route('student.my-courses'),
        ]);
    }

    public function module(
        StudentCourseEnrollment $enrollment,
        Module $module,
        StudentProgressService $progressService
    ): View|RedirectResponse {
        $this->authorizeEnrollment($enrollment);

        $enrollment->load('courseLevel.courseProgram');

        abort_unless($enrollment->courseLevel, 404);

        abort_unless(
            $module->course_level_id === $enrollment->course_level_id,
            404
        );

        abort_unless($module->is_active, 404);

        if (! $progressService->isModuleUnlocked($enrollment, $module)) {
            return redirect()
                ->route('student.learning-path', $enrollment)
                ->with('error', 'Please complete the previous module first.');
        }

        $progressService->markModuleInProgress($enrollment, $module);

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

        $moduleProgress = StudentModuleProgress::query()
            ->where('enrollment_id', $enrollment->id)
            ->where('module_id', $module->id)
            ->first();

        $practice = $module->practices->first();

        $latestPracticeAttempt = null;
        $practiceAttemptCount = 0;
        $canRetakePractice = false;

        if ($practice) {
            $latestPracticeAttempt = ModulePracticeAttempt::query()
                ->where('student_id', auth()->id())
                ->where('module_practice_id', $practice->id)
                ->whereIn('status', ['passed', 'failed', 'waiting_review'])
                ->latest('submitted_at')
                ->latest()
                ->first();

            $practiceAttemptCount = ModulePracticeAttempt::query()
                ->where('student_id', auth()->id())
                ->where('module_practice_id', $practice->id)
                ->whereIn('status', ['passed', 'failed', 'waiting_review'])
                ->count();

            $canRetakePractice = ! $practice->max_attempts
                || $practiceAttemptCount < (int) $practice->max_attempts;
        }

        return view('pages.student.module-material', [
            'enrollment' => $enrollment,
            'courseLevel' => $enrollment->courseLevel,
            'module' => $module,
            'materials' => $module->materials,
            'practices' => $module->practices,
            'moduleProgress' => $moduleProgress,
            'practice' => $practice,
            'latestPracticeAttempt' => $latestPracticeAttempt,
            'practiceAttemptCount' => $practiceAttemptCount,
            'canRetakePractice' => $canRetakePractice,
        ]);
    }

    public function completeModule(
        StudentCourseEnrollment $enrollment,
        Module $module,
        StudentProgressService $progressService
    ): RedirectResponse {
        $this->authorizeEnrollment($enrollment);

        $enrollment->load('courseLevel');

        abort_unless($enrollment->courseLevel, 404);

        abort_unless(
            $module->course_level_id === $enrollment->course_level_id,
            404
        );

        abort_unless($module->is_active, 404);

        if (! $progressService->isModuleUnlocked($enrollment, $module)) {
            return redirect()
                ->route('student.learning-path', $enrollment)
                ->with('error', 'Please complete the previous module first.');
        }

        $module->load([
            'practices' => function ($query) {
                $query->where('is_active', true);
            },
        ]);

        if ($module->practices->count() > 0) {
            return redirect()
                ->route('student.module-material', [
                    'enrollment' => $enrollment,
                    'module' => $module,
                ])
                ->with('error', 'Please submit the module practice to complete this module.');
        }

        $progressService->markModuleCompleted($enrollment, $module);

        return redirect()
            ->route('student.learning-path', $enrollment)
            ->with('success', 'Module has been marked as completed.');
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
