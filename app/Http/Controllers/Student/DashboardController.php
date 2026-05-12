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

        $activeCourseCount = StudentCourseEnrollment::query()
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->count();

        $completedCourseCount = StudentCourseEnrollment::query()
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->count();

        $pendingOrderCount = $pendingOrders->count();

        $rejectedOrderCount = Order::query()
            ->where('student_id', $student->id)
            ->where('status', 'rejected')
            ->count();

        $continueLearningCourses = StudentCourseEnrollment::query()
            ->with('courseLevel.courseProgram')
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->whereHas('courseLevel')
            ->latest('enrolled_at')
            ->latest()
            ->limit(4)
            ->get()
            ->map(function (StudentCourseEnrollment $enrollment) {
                $courseLevel = $enrollment->courseLevel;

                return [
                    'title' => $courseLevel?->name ?? 'Unknown Course',
                    'level' => $courseLevel?->courseProgram?->name ?? 'Course Program',
                    'progress' => (int) $enrollment->progress_percentage,
                    'image' => $this->courseImage($courseLevel),
                    'href' => route('student.learning-path', $enrollment),
                ];
            })
            ->all();

        $academicStatus = $this->academicStatus(
            $activeCourseCount,
            $pendingOrderCount
        );

        $welcomeDescription = $this->welcomeDescription(
            $activeCourseCount,
            $pendingOrderCount
        );

        return view('pages.student.dashboard', [
            'student' => $student,
            'pendingOrders' => $pendingOrders,
            'latestPendingOrder' => $latestPendingOrder,
            'activeCourseCount' => $activeCourseCount,
            'pendingOrderCount' => $pendingOrderCount,
            'completedCourseCount' => $completedCourseCount,
            'rejectedOrderCount' => $rejectedOrderCount,
            'continueLearningCourses' => $continueLearningCourses,
            'academicStatus' => $academicStatus,
            'welcomeDescription' => $welcomeDescription,
        ]);
    }

    private function academicStatus(int $activeCourseCount, int $pendingOrderCount): string
    {
        if ($activeCourseCount > 0) {
            return 'Academic Status: Active';
        }

        if ($pendingOrderCount > 0) {
            return 'Academic Status: Pending Enrollment';
        }

        return 'Academic Status: New Student';
    }

    private function welcomeDescription(int $activeCourseCount, int $pendingOrderCount): string
    {
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
