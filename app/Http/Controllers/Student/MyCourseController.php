<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StudentCourseEnrollment;
use Illuminate\View\View;

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

                return [
                    'title' => $courseLevel?->name ?? 'Unknown Course',
                    'level' => $courseLevel?->courseProgram?->name ?? 'Course Program',
                    'status' => $enrollment->status,
                    'statusLabel' => $this->enrollmentStatusLabel($enrollment->status),
                    'meta' => $enrollment->enrolled_at
                        ? 'Enrolled ' . $enrollment->enrolled_at->format('M d, Y')
                        : 'Enrollment active',
                    'progress' => (int) $enrollment->progress_percentage,
                    'progressLabel' => $enrollment->status === 'completed'
                        ? 'Course Completed'
                        : 'Course Progress',
                    'badge' => $this->courseBadge($courseLevel?->learning_mode, $courseLevel?->access_type),
                    'image' => $this->courseImage($courseLevel),
                    'primaryButton' => $enrollment->status === 'completed'
                        ? 'View Certificate'
                        : 'Continue Learning',
                    'secondaryButton' => 'Chat Admin',
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
                        'primaryButton' => 'Order Rejected',
                        'secondaryButton' => 'Chat Admin',
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
                    'primaryButton' => 'Module Locked',
                    'secondaryButton' => 'Chat Admin',
                ];
            });

        $courses = $enrollmentCourses
            ->concat($orderCourses)
            ->values()
            ->all();

        $activeCourseCount = collect($courses)
            ->where('status', 'active')
            ->count();

        $certificates = [];

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
        if ($courseLevel?->thumbnail_file) {
            return asset('storage/' . $courseLevel->thumbnail_file);
        }

        return 'https://placehold.co/800x600/F3F4F6/1E293B?text=Queens+English';
    }
}
