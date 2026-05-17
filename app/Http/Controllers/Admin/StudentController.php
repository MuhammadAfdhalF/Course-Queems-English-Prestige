<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');

        $students = User::query()
            ->where('role', 'student')
            ->with('studentProfile')
            ->withCount([
                'orders',
                'certificates',
                'testimonials',
                'enrollments',
                'enrollments as active_enrollments_count' => function ($query) {
                    $query->where('status', 'active');
                },
                'enrollments as completed_enrollments_count' => function ($query) {
                    $query->where('status', 'completed');
                },
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($studentQuery) use ($search) {
                    $studentQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhereHas('studentProfile', function ($profileQuery) use ($search) {
                            $profileQuery->where('whatsapp', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.students.index', [
            'students' => $students,
            'search' => $search,
            'status' => $status,
            'totalStudents' => User::query()->where('role', 'student')->count(),
            'activeStudents' => User::query()->where('role', 'student')->where('is_active', true)->count(),
            'inactiveStudents' => User::query()->where('role', 'student')->where('is_active', false)->count(),
        ]);
    }

    public function show(User $student): View
    {
        abort_unless($student->isStudent(), 404);

        $student->load([
            'studentProfile',
            'enrollments.courseLevel.courseProgram',
            'orders.courseLevel.courseProgram',
            'certificates.courseLevel.courseProgram',
            'testimonials.courseLevel.courseProgram',
            'modulePracticeAttempts.practice',
            'finalExamAttempts.finalExam.courseLevel.courseProgram',
        ]);

        $enrollments = $student->enrollments()
            ->with('courseLevel.courseProgram')
            ->latest('enrolled_at')
            ->latest()
            ->get();

        $orders = $student->orders()
            ->with('courseLevel.courseProgram')
            ->latest('order_date')
            ->latest()
            ->get();

        $certificates = $student->certificates()
            ->with('courseLevel.courseProgram')
            ->latest()
            ->get();

        $testimonials = $student->testimonials()
            ->with('courseLevel.courseProgram')
            ->latest()
            ->get();

        $practiceAttempts = $student->modulePracticeAttempts()
            ->with('practice')
            ->latest('submitted_at')
            ->latest()
            ->limit(8)
            ->get();

        $finalExamAttempts = $student->finalExamAttempts()
            ->with('finalExam.courseLevel.courseProgram')
            ->latest('submitted_at')
            ->latest()
            ->limit(8)
            ->get();

        $stats = [
            [
                'label' => 'Total Enrollments',
                'value' => $enrollments->count(),
                'description' => 'All course access records',
                'tone' => 'blue',
            ],
            [
                'label' => 'Active Courses',
                'value' => $enrollments->where('status', 'active')->count(),
                'description' => 'Currently learning',
                'tone' => 'emerald',
            ],
            [
                'label' => 'Completed Courses',
                'value' => $enrollments->where('status', 'completed')->count(),
                'description' => 'Finished enrollments',
                'tone' => 'blue',
            ],
            [
                'label' => 'Total Orders',
                'value' => $orders->count(),
                'description' => 'Course order history',
                'tone' => 'amber',
            ],
            [
                'label' => 'Certificates',
                'value' => $certificates->count(),
                'description' => 'All certificate records',
                'tone' => 'blue',
            ],
            [
                'label' => 'Testimonials',
                'value' => $testimonials->count(),
                'description' => 'Submitted testimonials',
                'tone' => 'emerald',
            ],
        ];

        return view('pages.admin.students.show', [
            'student' => $student,
            'profile' => $student->studentProfile,
            'stats' => $stats,
            'enrollments' => $enrollments,
            'orders' => $orders,
            'certificates' => $certificates,
            'testimonials' => $testimonials,
            'practiceAttempts' => $practiceAttempts,
            'finalExamAttempts' => $finalExamAttempts,
        ]);
    }
}
