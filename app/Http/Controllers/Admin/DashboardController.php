<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CourseLevel;
use App\Models\FinalExamAttempt;
use App\Models\ModulePracticeAttempt;
use App\Models\Order;
use App\Models\StudentCourseEnrollment;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalStudents = User::query()
            ->where('role', 'student')
            ->count();

        $activeEnrollments = StudentCourseEnrollment::query()
            ->where('status', 'active')
            ->count();

        $totalCourses = CourseLevel::query()
            ->where('is_active', true)
            ->count();

        $pendingOrders = Order::query()
            ->where('status', 'pending')
            ->count();

        $practiceWaitingReviewCount = ModulePracticeAttempt::query()
            ->where('status', 'waiting_review')
            ->count();

        $finalExamWaitingReviewCount = FinalExamAttempt::query()
            ->where('status', 'waiting_review')
            ->count();

        $waitingReviews = $practiceWaitingReviewCount + $finalExamWaitingReviewCount;

        $issuedCertificates = Certificate::query()
            ->where('status', 'issued')
            ->count();

        $lockedCertificates = Certificate::query()
            ->where('status', 'locked')
            ->count();

        $thisMonthRevenue = Order::query()
            ->where('status', 'approved')
            ->whereYear('approved_at', now()->year)
            ->whereMonth('approved_at', now()->month)
            ->sum('price');

        $yearToDateRevenue = Order::query()
            ->where('status', 'approved')
            ->whereYear('approved_at', now()->year)
            ->sum('price');

        $metrics = [
            [
                'title' => 'Total Students',
                'value' => number_format($totalStudents),
                'description' => 'Registered students',
                'accent' => 'blue',
                'icon' => 'users',
            ],
            [
                'title' => 'Active Enrollments',
                'value' => number_format($activeEnrollments),
                'description' => 'Currently learning',
                'accent' => 'blue',
                'icon' => 'book',
            ],
            [
                'title' => 'Total Courses',
                'value' => number_format($totalCourses),
                'description' => 'Active course levels',
                'accent' => 'blue',
                'icon' => 'book',
            ],
            [
                'title' => 'Pending Orders',
                'value' => number_format($pendingOrders),
                'description' => 'Action required',
                'accent' => 'gold',
                'icon' => 'cart',
            ],
            [
                'title' => 'Waiting Reviews',
                'value' => number_format($waitingReviews),
                'description' => 'Practice & final exam',
                'accent' => 'gold',
                'icon' => 'certificate',
            ],
            [
                'title' => 'Issued Certificates',
                'value' => number_format($issuedCertificates),
                'description' => 'Unlocked certificates',
                'accent' => 'blue',
                'icon' => 'certificate',
            ],
            [
                'title' => 'Locked Certificates',
                'value' => number_format($lockedCertificates),
                'description' => 'Waiting testimonials',
                'accent' => 'gold',
                'icon' => 'certificate',
            ],
            [
                'title' => 'This Month Revenue',
                'value' => 'Rp ' . number_format((float) $thisMonthRevenue, 0, ',', '.'),
                'description' => 'Approved orders',
                'accent' => 'blue',
                'icon' => 'cart',
            ],
        ];

        $monthlyRevenue = $this->monthlyRevenue();
        $revenueChartMax = max(1, collect($monthlyRevenue)->max('total'));

        $monthlyRevenue = collect($monthlyRevenue)
            ->map(function (array $month) use ($revenueChartMax) {
                $height = $month['total'] > 0
                    ? max(10, round(($month['total'] / $revenueChartMax) * 100))
                    : 8;

                return array_merge($month, [
                    'height' => $height,
                ]);
            })
            ->all();

        $activities = Order::query()
            ->with(['student', 'courseLevel.courseProgram'])
            ->latest('order_date')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function (Order $order) {
                $studentName = $order->student?->name ?? 'Unknown Student';

                return [
                    'initials' => $this->initials($studentName),
                    'name' => $studentName,
                    'description' => $this->activityDescription($order),
                    'variant' => $this->statusVariant($order->status),
                    'avatar' => $this->avatarVariant($order->status),
                ];
            })
            ->all();

        $recentTransactions = Order::query()
            ->with(['student', 'courseLevel.courseProgram'])
            ->latest('order_date')
            ->latest()
            ->limit(8)
            ->get();

        $totalTransactions = Order::query()->count();

        $transactions = $recentTransactions
            ->map(function (Order $order) {
                return [
                    'orderId' => $order->order_code ?? ('#ORDER-' . $order->id),
                    'student' => $order->student?->name ?? 'Unknown Student',
                    'course' => $order->courseLevel?->name ?? 'Unknown Course',
                    'price' => 'Rp ' . number_format((float) $order->price, 0, ',', '.'),
                    'status' => $this->statusLabel($order->status),
                    'statusVariant' => $this->statusVariant($order->status),
                    'date' => $order->order_date?->format('d M Y') ?? $order->created_at?->format('d M Y') ?? '-',
                    'href' => route('admin.orders.index'),
                ];
            })
            ->all();

        $practiceReviews = ModulePracticeAttempt::query()
            ->with(['student', 'practice.module.courseLevel.courseProgram'])
            ->where('status', 'waiting_review')
            ->latest('submitted_at')
            ->latest()
            ->limit(4)
            ->get()
            ->map(function (ModulePracticeAttempt $attempt) {
                return [
                    'type' => 'Practice',
                    'student' => $attempt->student?->name ?? 'Unknown Student',
                    'assessment' => $attempt->practice?->title ?? 'Module Practice',
                    'course' => $attempt->practice?->module?->courseLevel?->name ?? null,
                    'submittedAt' => $attempt->submitted_at?->format('d M Y H:i') ?? '-',
                    'href' => route('admin.course-management.practice-reviews.show', $attempt),
                ];
            });

        $finalExamReviews = FinalExamAttempt::query()
            ->with(['student', 'finalExam.courseLevel.courseProgram'])
            ->where('status', 'waiting_review')
            ->latest('submitted_at')
            ->latest()
            ->limit(4)
            ->get()
            ->map(function (FinalExamAttempt $attempt) {
                return [
                    'type' => 'Final Exam',
                    'student' => $attempt->student?->name ?? 'Unknown Student',
                    'assessment' => $attempt->finalExam?->title ?? 'Final Exam',
                    'course' => $attempt->finalExam?->courseLevel?->name ?? null,
                    'submittedAt' => $attempt->submitted_at?->format('d M Y H:i') ?? '-',
                    'href' => route('admin.course-management.final-exam-reviews.show', $attempt),
                ];
            });

        $waitingReviewItems = $practiceReviews
            ->concat($finalExamReviews)
            ->sortByDesc('submittedAt')
            ->take(6)
            ->values()
            ->all();

        $actionItems = [
            [
                'title' => 'Pending Orders',
                'count' => $pendingOrders,
                'description' => 'Course orders waiting for admin approval.',
                'href' => route('admin.orders.index'),
                'buttonLabel' => 'View Orders',
                'tone' => 'amber',
            ],
            [
                'title' => 'Practice Reviews',
                'count' => $practiceWaitingReviewCount,
                'description' => 'Module practice attempts waiting for manual review.',
                'href' => route('admin.course-management.programs.index'),
                'buttonLabel' => 'Open Courses',
                'tone' => 'blue',
            ],
            [
                'title' => 'Final Exam Reviews',
                'count' => $finalExamWaitingReviewCount,
                'description' => 'Final exam attempts waiting for manual grading.',
                'href' => route('admin.course-management.programs.index'),
                'buttonLabel' => 'Open Courses',
                'tone' => 'blue',
            ],
            [
                'title' => 'Locked Certificates',
                'count' => $lockedCertificates,
                'description' => 'Certificates waiting for student testimonials.',
                'href' => route('admin.course-management.certificates.index'),
                'buttonLabel' => 'View Certificates',
                'tone' => 'emerald',
            ],
        ];

        return view('pages.admin.dashboard', [
            'metrics' => $metrics,
            'activities' => $activities,
            'transactions' => $transactions,
            'totalTransactions' => $totalTransactions,
            'monthlyRevenue' => $monthlyRevenue,
            'thisMonthRevenue' => $thisMonthRevenue,
            'yearToDateRevenue' => $yearToDateRevenue,
            'currentYear' => now()->year,
            'actionItems' => $actionItems,
            'waitingReviewItems' => $waitingReviewItems,
        ]);
    }

    private function monthlyRevenue(): array
    {
        $revenues = Order::query()
            ->selectRaw('MONTH(approved_at) as month_number, SUM(price) as total')
            ->where('status', 'approved')
            ->whereYear('approved_at', now()->year)
            ->whereNotNull('approved_at')
            ->groupBy('month_number')
            ->pluck('total', 'month_number');

        return collect(range(1, 12))
            ->map(function (int $month) use ($revenues) {
                return [
                    'month' => Carbon::create(null, $month, 1)->format('M'),
                    'total' => (float) ($revenues[$month] ?? 0),
                ];
            })
            ->values()
            ->all();
    }
    private function initials(string $name): string
    {
        $initials = collect(explode(' ', trim($name)))
            ->filter()
            ->take(2)
            ->map(fn(string $word) => strtoupper(substr($word, 0, 1)))
            ->implode('');

        return $initials ?: 'U';
    }

    private function activityDescription(Order $order): string
    {
        $courseName = $order->courseLevel?->name ?? 'Unknown Course';

        return match ($order->status) {
            'approved' => 'Order approved for ' . $courseName,
            'rejected' => 'Order rejected for ' . $courseName,
            default => 'Ordered ' . $courseName,
        };
    }

    private function statusLabel(?string $status): string
    {
        return match ($status) {
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'pending' => 'Pending',
            default => ucfirst((string) $status),
        };
    }

    private function statusVariant(?string $status): string
    {
        return match ($status) {
            'approved' => 'completed',
            'rejected' => 'cancelled',
            'pending' => 'pending',
            default => 'pending',
        };
    }

    private function avatarVariant(?string $status): string
    {
        return match ($status) {
            'approved' => 'green',
            'rejected' => 'rose',
            default => 'blue',
        };
    }
}
