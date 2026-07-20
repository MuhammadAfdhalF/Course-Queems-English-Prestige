<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
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

        $latestRejectedOrder = Order::query()
            ->with('courseLevel.courseProgram')
            ->where('student_id', $student->id)
            ->where('status', 'rejected')
            ->latest('rejected_at')
            ->latest()
            ->first();

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

        $lockedCertificates = Certificate::query()
            ->with('courseLevel.courseProgram')
            ->where('student_id', $student->id)
            ->where('status', 'locked')
            ->latest()
            ->get();

        $latestLockedCertificate = $lockedCertificates->first();

        $issuedCertificateCount = Certificate::query()
            ->where('student_id', $student->id)
            ->where('status', 'issued')
            ->count();

        $latestIssuedCertificate = Certificate::query()
            ->with('courseLevel.courseProgram')
            ->where('student_id', $student->id)
            ->where('status', 'issued')
            ->latest('issued_at')
            ->latest()
            ->first();

        $averageProgress = $activeEnrollments->count() > 0
            ? (int) round($activeEnrollments->avg('progress_percentage'))
            : 0;

        $continueLearningCourses = $activeEnrollments
            ->sortBy(function (StudentCourseEnrollment $enrollment) {
                $progress = (int) $enrollment->progress_percentage;

                if ($progress > 0 && $progress < 100) {
                    return 0;
                }

                if ($progress <= 0) {
                    return 1;
                }

                return 2;
            })
            ->take(3)
            ->map(function (StudentCourseEnrollment $enrollment) {
                $courseLevel = $enrollment->courseLevel;
                $progress = (int) $enrollment->progress_percentage;

                return [
                    'title' => $courseLevel?->name ?? 'Unknown Course',
                    'program' => $courseLevel?->courseProgram?->name ?? 'Course Program',
                    'progress' => $progress,
                    'image' => $this->courseImage($courseLevel),
                    'poster' => $this->coursePoster($courseLevel),
                    'thumbnailType' => $courseLevel?->thumbnail_type ?? 'image',
                    'href' => route('student.learning-path', $enrollment),
                    'statusLabel' => $this->learningStatusLabel($progress),
                    'buttonText' => $this->learningButtonText($progress),
                ];
            })
            ->values()
            ->all();

        $primaryLearningUrl = count($continueLearningCourses) > 0
            ? $continueLearningCourses[0]['href']
            : route('courses');

        $primaryLearningButton = count($continueLearningCourses) > 0
            ? $continueLearningCourses[0]['buttonText']
            : 'Explore Courses';

        $academicStatus = $this->academicStatus(
            $activeCourseCount,
            $pendingOrderCount,
            $averageProgress
        );

        $welcomeDescription = $this->welcomeDescription(
            $activeCourseCount,
            $pendingOrderCount,
            $averageProgress,
            $lockedCertificates->count()
        );

        return view('pages.student.dashboard', [
            'student' => $student,
            'pendingOrders' => $pendingOrders,
            'latestPendingOrder' => $latestPendingOrder,
            'latestRejectedOrder' => $latestRejectedOrder,
            'activeCourseCount' => $activeCourseCount,
            'pendingOrderCount' => $pendingOrderCount,
            'completedCourseCount' => $completedCourseCount,
            'rejectedOrderCount' => $rejectedOrderCount,
            'lockedCertificateCount' => $lockedCertificates->count(),
            'latestLockedCertificate' => $latestLockedCertificate,
            'issuedCertificateCount' => $issuedCertificateCount,
            'latestIssuedCertificate' => $latestIssuedCertificate,
            'continueLearningCourses' => $continueLearningCourses,
            'averageProgress' => $averageProgress,
            'academicStatus' => $academicStatus,
            'welcomeDescription' => $welcomeDescription,
            'primaryLearningUrl' => $primaryLearningUrl,
            'primaryLearningButton' => $primaryLearningButton,
        ]);
    }

    private function academicStatus(int $activeCourseCount, int $pendingOrderCount, int $averageProgress): string
    {
        if ($activeCourseCount > 0 && $averageProgress >= 100) {
            return 'Modules Completed';
        }

        if ($activeCourseCount > 0) {
            return 'Active Learner';
        }

        if ($pendingOrderCount > 0) {
            return 'Waiting Confirmation';
        }

        return 'New Student';
    }

    private function welcomeDescription(
        int $activeCourseCount,
        int $pendingOrderCount,
        int $averageProgress,
        int $lockedCertificateCount
    ): string {
        if ($lockedCertificateCount > 0) {
            return 'Your certificate is almost ready. Submit your testimonial to unlock your digital certificate.';
        }

        if ($activeCourseCount > 0 && $averageProgress >= 100) {
            return 'Great job completing your learning modules. You can review your course or continue to your next academic step.';
        }

        if ($activeCourseCount > 0) {
            return 'Continue your English journey today and keep building steady progress through your active course.';
        }

        if ($pendingOrderCount > 0) {
            return 'Your course order is being reviewed. Our admin will contact you via WhatsApp for the next step.';
        }

        return 'Start your English learning journey with Queens English Prestige and explore the available course programs.';
    }

    private function learningStatusLabel(int $progress): string
    {
        if ($progress >= 100) {
            return 'Modules completed';
        }

        if ($progress > 0) {
            return 'In progress';
        }

        return 'Ready to start';
    }

    private function learningButtonText(int $progress): string
    {
        if ($progress >= 100) {
            return 'Review Course';
        }

        if ($progress > 0) {
            return 'Continue Learning';
        }

        return 'Start Learning';
    }

    private function courseImage($courseLevel): string
    {
        if ($courseLevel?->thumbnail_type === 'image' && $courseLevel?->thumbnail_file) {
            return asset('storage/' . $courseLevel->thumbnail_file);
        }

        if ($courseLevel?->video_poster_file) {
            return asset('storage/' . $courseLevel->video_poster_file);
        }

        return 'https://placehold.co/800x500/E9ECEF/1E293B?text=Queens+English';
    }

    private function coursePoster($courseLevel): ?string
    {
        if ($courseLevel?->video_poster_file) {
            return asset('storage/' . $courseLevel->video_poster_file);
        }

        return null;
    }
}
