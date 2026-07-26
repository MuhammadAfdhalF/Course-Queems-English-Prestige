<?php

namespace App\Services;

use App\Models\Module;
use App\Models\StudentCourseEnrollment;
use App\Models\StudentModuleProgress;

class StudentProgressService
{
    public function markModuleInProgress(StudentCourseEnrollment $enrollment, Module $module): void
    {
        StudentModuleProgress::query()->firstOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'module_id' => $module->id,
            ],
            [
                'status' => 'in_progress',
                'progress_percentage' => 0,
                'started_at' => now(),
            ]
        );
    }

    public function markModuleCompleted(StudentCourseEnrollment $enrollment, Module $module): void
    {
        StudentModuleProgress::query()->updateOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'module_id' => $module->id,
            ],
            [
                'status' => 'completed',
                'progress_percentage' => 100,
                'started_at' => now(),
                'completed_at' => now(),
            ]
        );

        $this->recalculateEnrollmentProgress($enrollment);
    }

    public function recalculateEnrollmentProgress(StudentCourseEnrollment $enrollment): void
    {
        $enrollment->loadMissing('courseLevel.modules');

        $totalModules = $enrollment->courseLevel
            ? $enrollment->courseLevel->modules()
            ->where('is_active', true)
            ->count()
            : 0;

        if ($totalModules <= 0) {
            $enrollment->update([
                'progress_percentage' => 0,
            ]);

            return;
        }

        $completedModules = StudentModuleProgress::query()
            ->where('enrollment_id', $enrollment->id)
            ->where('status', 'completed')
            ->count();

        $progress = round(($completedModules / $totalModules) * 100, 2);

        $enrollment->update([
            'progress_percentage' => $progress,
        ]);
    }

    public function isPracticeQualifying(StudentCourseEnrollment $enrollment, \App\Models\ModulePractice $practice): bool
    {
        if (! $practice->is_active) {
            return true;
        }

        $attempts = \App\Models\ModulePracticeAttempt::query()
            ->where('student_id', $enrollment->student_id)
            ->where('module_practice_id', $practice->id)
            ->whereNotNull('submitted_at')
            ->whereNotNull('graded_at')
            ->get();

        foreach ($attempts as $attempt) {
            $rawResultMode = $attempt->result_mode;
            $resultModeStr = $rawResultMode instanceof \App\Enums\AssessmentResultMode
                ? $rawResultMode->value
                : (string) $rawResultMode;

            if ($resultModeStr === 'score_only') {
                return true;
            }

            if ($resultModeStr === 'pass_fail' && ($attempt->is_passed === true || $attempt->status === 'passed')) {
                return true;
            }
        }

        return false;
    }

    public function evaluateAndSyncModuleCompletion(StudentCourseEnrollment $enrollment, Module $module): bool
    {
        $practices = $module->practices()->where('is_active', true)->get();
        foreach ($practices as $practice) {
            if (! $this->isPracticeQualifying($enrollment, $practice)) {
                return false;
            }
        }

        $this->markModuleCompleted($enrollment, $module);
        return true;
    }

    public function isModuleUnlocked(StudentCourseEnrollment $enrollment, Module $module): bool
    {
        $modules = $enrollment->courseLevel
            ->modules()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $targetIndex = $modules->search(fn(Module $item) => $item->id === $module->id);

        if ($targetIndex === false) {
            return false;
        }

        if ($targetIndex === 0) {
            return true;
        }

        $completedModuleIds = StudentModuleProgress::query()
            ->where('enrollment_id', $enrollment->id)
            ->where('status', 'completed')
            ->pluck('module_id')
            ->all();

        for ($index = 0; $index < $targetIndex; $index++) {
            if (! in_array($modules[$index]->id, $completedModuleIds, true)) {
                return false;
            }
        }

        return true;
    }
}
