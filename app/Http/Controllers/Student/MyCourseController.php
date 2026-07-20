<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StudentCourseEnrollment;
use Illuminate\View\View;
use App\Models\Certificate;


class MyCourseController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();

        $enrollmentCourses = StudentCourseEnrollment::query()
            ->with([
                'courseLevel.courseProgram',
            ])
            ->where('student_id', $student->id)
            ->latest('enrolled_at')
            ->latest()
            ->get()
            ->map(function (StudentCourseEnrollment $enrollment) {
                $courseLevel = $enrollment->courseLevel;
                $progress = (int) $enrollment->progress_percentage;

                return [
                    'title' => $courseLevel?->name ?? 'Unknown Course',
                    'level' => $courseLevel?->courseProgram?->name ?? 'Course Program',
                    'status' => $enrollment->status,
                    'statusLabel' => $this->enrollmentStatusLabel($enrollment->status),
                    'meta' => $enrollment->enrolled_at
                        ? 'Enrolled ' . $enrollment->enrolled_at->format('M d, Y')
                        : 'Enrollment active',
                    'progress' => $progress,
                    'progressLabel' => $this->courseProgressLabel($enrollment->status, $progress),
                    'badge' => $this->courseBadge($courseLevel?->learning_mode, $courseLevel?->access_type),
                    'image' => $this->courseImage($courseLevel),
                    'poster' => $this->coursePoster($courseLevel),
                    'thumbnailType' => $courseLevel?->thumbnail_type ?? 'image',
                    'primaryButton' => $this->primaryButtonLabel($enrollment->status, $progress),
                    'secondaryButton' => 'Chat Admin',
                    'primaryHref' => route('student.learning-path', $enrollment),
                ];
            });

        $orderCourses = Order::query()
            ->with([
                'courseLevel.courseProgram',
            ])
            ->where('student_id', $student->id)
            ->whereIn('status', ['pending', 'rejected'])
            ->latest('order_date')
            ->latest()
            ->get()
            ->map(function (Order $order) {
                $courseLevel = $order->courseLevel;

                if ($order->status === 'rejected') {
                    return [
                        'title' => $courseLevel?->name ?? 'Unknown Course',
                        'level' => $courseLevel?->courseProgram?->name ?? 'Course Program',
                        'status' => 'rejected',
                        'statusLabel' => 'Rejected',
                        'meta' => $order->rejected_at
                            ? 'Rejected ' . $order->rejected_at->format('M d, Y')
                            : 'Order rejected',
                        'progress' => 0,
                        'progressLabel' => 'This order was rejected. Please contact admin for more information.',
                        'badge' => 'REJECTED',
                        'image' => $this->courseImage($courseLevel),
                        'poster' => $this->coursePoster($courseLevel),
                        'thumbnailType' => $courseLevel?->thumbnail_type ?? 'image',
                        'primaryButton' => 'Order Rejected',
                        'secondaryButton' => 'Chat Admin',
                        'primaryHref' => '#',
                    ];
                }

                return [
                    'title' => $courseLevel?->name ?? 'Unknown Course',
                    'level' => $courseLevel?->courseProgram?->name ?? 'Course Program',
                    'status' => 'pending',
                    'statusLabel' => 'Pending Enrollment',
                    'meta' => $order->order_date
                        ? 'Ordered ' . $order->order_date->format('M d, Y')
                        : 'Waiting for approval',
                    'progress' => 0,
                    'progressLabel' => 'Waiting for administration approval. Our admin will contact you via WhatsApp.',
                    'badge' => 'PENDING',
                    'image' => $this->courseImage($courseLevel),
                    'poster' => $this->coursePoster($courseLevel),
                    'thumbnailType' => $courseLevel?->thumbnail_type ?? 'image',
                    'primaryButton' => 'Module Locked',
                    'secondaryButton' => 'Chat Admin',
                    'primaryHref' => '#',
                ];
            });

        $courses = $enrollmentCourses
            ->concat($orderCourses)
            ->values()
            ->all();

        $activeCourseCount = collect($courses)
            ->where('status', 'active')
            ->count();
        $certificates = Certificate::query()
            ->with('courseLevel.courseProgram')
            ->where('student_id', $student->id)
            ->latest()
            ->get()
            ->map(function (Certificate $certificate) {
                $isLocked = $certificate->status === 'locked';
                $isIssued = $certificate->status === 'issued';

                return [
                    'certificateId' => $certificate->id,
                    'title' => $certificate->courseLevel?->name ?? 'Unknown Course',
                    'id' => $certificate->certificate_number,
                    'locked' => $isLocked,
                    'issued' => $isIssued,
                    'status' => $certificate->status,
                    'note' => match ($certificate->status) {
                        'locked' => 'Write testimonial to unlock',
                        'issued' => 'Certificate issued',
                        'revoked' => 'Certificate revoked',
                        default => '',
                    },
                    'href' => match ($certificate->status) {
                        'locked' => route('student.testimoni'),
                        'issued' => route('student.certificates.show', $certificate),
                        default => '#',
                    },
                ];
            })
            ->all();

        return view('pages.student.my-courses', [
            'courses' => $courses,
            'certificates' => $certificates,
            'activeCourseCount' => $activeCourseCount,
            'totalCourseCount' => count($courses),
        ]);
    }

    private function enrollmentStatusLabel(string $status): string
    {
        return match ($status) {
            'completed' => 'Completed',
            'expired' => 'Expired',
            'cancelled' => 'Cancelled',
            default => 'Active',
        };
    }

    private function courseProgressLabel(string $status, int $progress): string
    {
        if ($status === 'completed') {
            return 'Course Completed';
        }

        if ($progress <= 0) {
            return 'Ready to start';
        }

        if ($progress >= 100) {
            return 'All modules completed';
        }

        return 'Course Progress';
    }

    private function primaryButtonLabel(string $status, int $progress): string
    {
        if ($status === 'completed') {
            return 'Review Course';
        }

        if (in_array($status, ['expired', 'cancelled'], true)) {
            return 'Course Unavailable';
        }

        if ($progress <= 0) {
            return 'Start Learning';
        }

        if ($progress >= 100) {
            return 'Review Course';
        }

        return 'Continue Learning';
    }

    private function courseBadge(?string $learningMode, ?string $accessType): string
    {
        $modeLabel = match ($learningMode) {
            'offline' => 'OFFLINE',
            'hybrid' => 'HYBRID',
            default => 'ONLINE',
        };

        $accessLabel = $accessType === 'limited' ? 'LIMITED' : 'LIFETIME';

        return $modeLabel . ' • ' . $accessLabel;
    }

    private function courseImage($courseLevel): string
    {
        if ($courseLevel?->thumbnail_type === 'image' && $courseLevel?->thumbnail_file) {
            return asset('storage/' . $courseLevel->thumbnail_file);
        }

        if ($courseLevel?->video_poster_file) {
            return asset('storage/' . $courseLevel->video_poster_file);
        }

        return 'https://placehold.co/800x600/F3F4F6/1E293B?text=Queens+English';
    }

    private function coursePoster($courseLevel): ?string
    {
        if ($courseLevel?->video_poster_file) {
            return asset('storage/' . $courseLevel->video_poster_file);
        }

        return null;
    }
}
