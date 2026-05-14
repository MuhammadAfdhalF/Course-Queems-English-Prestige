<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StudentCourseEnrollment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();

        $pendingOrders = Order::query()
            ->with('courseLevel.courseProgram')
            ->where('student_id', $student->id)
            ->where('status', 'pending')
            ->latest('order_date')
            ->latest()
            ->get();

        $latestPendingOrder = $pendingOrders->first();

        $activeEnrollments = StudentCourseEnrollment::query()
            ->with([
                'courseLevel.courseProgram',
                'courseLevel.finalExams' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->whereHas('courseLevel')
            ->latest('enrolled_at')
            ->latest()
            ->get();

        $completedCourseCount = StudentCourseEnrollment::query()
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->count();

        $activeCourseCount = $activeEnrollments->count();
        $pendingOrderCount = $pendingOrders->count();

        $rejectedOrderCount = Order::query()
            ->where('student_id', $student->id)
            ->where('status', 'rejected')
            ->count();

        $finalExamAvailableCount = $activeEnrollments
            ->filter(function (StudentCourseEnrollment $enrollment) {
                return $enrollment->courseLevel?->finalExams?->count() > 0;
            })
            ->count();

        $averageProgress = $activeEnrollments->count() > 0
            ? (int) round($activeEnrollments->avg('progress_percentage'))
            : 0;

        $continueLearningCourses = $activeEnrollments
            ->sortBy(function (StudentCourseEnrollment $enrollment) {
                $progress = (int) $enrollment->progress_percentage;

                if ($progress <= 0) {
                    return 1;
                }

                if ($progress < 100) {
                    return 0;
                }

                return 2;
            })
            ->take(4)
            ->map(function (StudentCourseEnrollment $enrollment) {
                $courseLevel = $enrollment->courseLevel;
                $progress = (int) $enrollment->progress_percentage;

                return [
                    'title' => $courseLevel?->name ?? 'Unknown Course',
                    'level' => $courseLevel?->courseProgram?->name ?? 'Course Program',
                    'progress' => $progress,
                    'image' => $this->courseImage($courseLevel),
                    'href' => route('student.learning-path', $enrollment),
                ];
            })
            ->values()
            ->all();

        $academicStatus = $this->academicStatus(
            $activeCourseCount,
            $pendingOrderCount,
            $averageProgress
        );

        $welcomeDescription = $this->welcomeDescription(
            $activeCourseCount,
            $pendingOrderCount,
            $averageProgress,
            $finalExamAvailableCount
        );

        return view('pages.student.dashboard', [
            'student' => $student,
            'pendingOrders' => $pendingOrders,
            'latestPendingOrder' => $latestPendingOrder,
            'activeCourseCount' => $activeCourseCount,
            'pendingOrderCount' => $pendingOrderCount,
            'completedCourseCount' => $completedCourseCount,
            'rejectedOrderCount' => $rejectedOrderCount,
            'finalExamAvailableCount' => $finalExamAvailableCount,
            'continueLearningCourses' => $continueLearningCourses,
            'academicStatus' => $academicStatus,
            'welcomeDescription' => $welcomeDescription,
        ]);
    }

    private function academicStatus(int $activeCourseCount, int $pendingOrderCount, int $averageProgress): string
    {
        if ($activeCourseCount > 0 && $averageProgress >= 100) {
            return 'Academic Status: Modules Completed';
        }

        if ($activeCourseCount > 0) {
            return 'Academic Status: Active';
        }

        if ($pendingOrderCount > 0) {
            return 'Academic Status: Pending Enrollment';
        }

        return 'Academic Status: New Student';
    }

    private function welcomeDescription(
        int $activeCourseCount,
        int $pendingOrderCount,
        int $averageProgress,
        int $finalExamAvailableCount
    ): string {
        if ($activeCourseCount > 0 && $averageProgress >= 100 && $finalExamAvailableCount > 0) {
            return 'You have completed your learning modules. Review your course materials and get ready for the final exam when you are prepared.';
        }

        if ($activeCourseCount > 0 && $averageProgress >= 100) {
            return 'You have completed your learning modules. You can review your course materials anytime from My Courses.';
        }

        if ($activeCourseCount > 0) {
            return 'Ready to continue your English journey? Pick up from your active course and keep building your progress.';
        }

        if ($pendingOrderCount > 0) {
            return 'Your course order is currently being reviewed. Our admin will contact you via WhatsApp for the next step.';
        }

        return 'Welcome to your academic portal. Explore available courses and place your first order to start learning with Queens English Prestige.';
    }

    private function courseImage($courseLevel): string
    {
        if ($courseLevel?->thumbnail_file) {
            return asset('storage/' . $courseLevel->thumbnail_file);
        }

        return 'https://placehold.co/800x500/E9ECEF/1E293B?text=Queens+English';
    }
}
