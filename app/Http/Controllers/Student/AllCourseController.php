<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\Order;
use App\Models\StudentCourseEnrollment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AllCourseController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user();

        $selectedMode = $request->query('mode');
        $selectedProgram = $request->query('program');
        $selectedStatus = $request->query('status');

        $programs = CourseProgram::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $enrollments = StudentCourseEnrollment::query()
            ->where('student_id', $student->id)
            ->latest('enrolled_at')
            ->latest()
            ->get()
            ->keyBy('course_level_id');

        $latestOrders = Order::query()
            ->where('student_id', $student->id)
            ->latest('order_date')
            ->latest()
            ->get()
            ->unique('course_level_id')
            ->keyBy('course_level_id');

        $courseLevels = CourseLevel::query()
            ->with('courseProgram')
            ->where('is_active', true)
            ->whereHas('courseProgram', function ($query) {
                $query->where('is_active', true);
            })
            ->when(
                in_array($selectedMode, ['online', 'offline', 'hybrid'], true),
                function ($query) use ($selectedMode) {
                    $query->where('learning_mode', $selectedMode);
                }
            )
            ->when($selectedProgram, function ($query) use ($selectedProgram) {
                $query->whereHas('courseProgram', function ($programQuery) use ($selectedProgram) {
                    $programQuery->where('slug', $selectedProgram);
                });
            })
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $courseItems = $courseLevels
            ->map(function (CourseLevel $courseLevel) use ($enrollments, $latestOrders) {
                $enrollment = $enrollments->get($courseLevel->id);
                $latestOrder = $latestOrders->get($courseLevel->id);

                $statusData = $this->resolveStudentCourseStatus($courseLevel, $enrollment, $latestOrder);

                return array_merge([
                    'id' => $courseLevel->id,
                    'title' => $courseLevel->name,
                    'level' => $courseLevel->courseProgram?->name ?? 'Course Program',
                    'mode' => $this->formatLearningMode($courseLevel->learning_mode),
                    'price' => 'Rp ' . number_format((float) $courseLevel->price, 0, ',', '.'),
                    'description' => $courseLevel->short_description ?: 'Course description will be available soon.',
                    'image' => $courseLevel->thumbnail_file
                        ? asset('storage/' . $courseLevel->thumbnail_file)
                        : 'https://placehold.co/800x500/EEF3FF/2457E6?text=Queens+English',
                ], $statusData);
            })
            ->when(
                in_array($selectedStatus, ['available', 'enrolled', 'completed', 'pending', 'rejected'], true),
                function ($items) use ($selectedStatus) {
                    return $items->where('status', $selectedStatus);
                }
            )
            ->values()
            ->all();

        return view('pages.student.all-courses', [
            'programs' => $programs,
            'courseItems' => $courseItems,
            'selectedMode' => $selectedMode,
            'selectedProgram' => $selectedProgram,
            'selectedStatus' => $selectedStatus,
        ]);
    }

    private function resolveStudentCourseStatus(
        CourseLevel $courseLevel,
        ?StudentCourseEnrollment $enrollment,
        ?Order $latestOrder
    ): array {
        if ($enrollment && $enrollment->status === 'active') {
            return [
                'status' => 'enrolled',
                'statusLabel' => 'Enrolled',
                'statusClass' => 'bg-emerald-50 text-emerald-700',
                'buttonText' => $enrollment->progress_percentage > 0 ? 'Continue Learning' : 'Start Learning',
                'href' => route('student.learning-path', $enrollment),
                'buttonClass' => 'bg-[var(--color-brand-blue)] text-white hover:opacity-90',
                'disabled' => false,
            ];
        }

        if ($enrollment && $enrollment->status === 'completed') {
            return [
                'status' => 'completed',
                'statusLabel' => 'Completed',
                'statusClass' => 'bg-blue-50 text-blue-700',
                'buttonText' => 'Review Course',
                'href' => route('student.learning-path', $enrollment),
                'buttonClass' => 'bg-[var(--color-brand-blue)] text-white hover:opacity-90',
                'disabled' => false,
            ];
        }

        if ($latestOrder && $latestOrder->status === 'pending') {
            return [
                'status' => 'pending',
                'statusLabel' => 'Waiting Approval',
                'statusClass' => 'bg-amber-50 text-amber-700',
                'buttonText' => 'Waiting Approval',
                'href' => route('courses.show', $courseLevel),
                'buttonClass' => 'bg-slate-100 text-slate-500 cursor-not-allowed',
                'disabled' => true,
            ];
        }

        if ($latestOrder && $latestOrder->status === 'rejected') {
            return [
                'status' => 'rejected',
                'statusLabel' => 'Rejected',
                'statusClass' => 'bg-rose-50 text-rose-700',
                'buttonText' => 'Order Again',
                'href' => route('courses.order.create', $courseLevel),
                'buttonClass' => 'bg-rose-50 text-rose-700 hover:bg-rose-100',
                'disabled' => false,
            ];
        }

        return [
            'status' => 'available',
            'statusLabel' => 'Available',
            'statusClass' => 'bg-emerald-50 text-emerald-700',
            'buttonText' => 'View Detail',
            'href' => route('courses.show', $courseLevel),
            'buttonClass' => 'bg-[var(--color-brand-blue)] text-white hover:opacity-90',
            'disabled' => false,
        ];
    }

    private function formatLearningMode(?string $learningMode): string
    {
        return match ($learningMode) {
            'offline' => 'Offline',
            'hybrid' => 'Hybrid',
            default => 'Online',
        };
    }
}
