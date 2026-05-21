<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\FinalExamAttempt;
use App\Models\ModulePracticeAttempt;
use App\Models\Order;
use App\Models\StudentCourseEnrollment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CourseAccessController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $source = $request->query('source', 'all');
        $program = $request->query('program');

        $programs = CourseProgram::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $enrollments = StudentCourseEnrollment::query()
            ->with([
                'student.studentProfile',
                'courseLevel.courseProgram',
                'order',
                'createdBy',
                'certificate',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($accessQuery) use ($search) {
                    $accessQuery
                        ->whereHas('student', function ($studentQuery) use ($search) {
                            $studentQuery
                                ->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('courseLevel', function ($courseQuery) use ($search) {
                            $courseQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when(in_array($status, ['active', 'completed', 'cancelled', 'expired'], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when(in_array($source, ['order', 'manual'], true), function ($query) use ($source) {
                $query->where('enrollment_source', $source);
            })
            ->when($program, function ($query) use ($program) {
                $query->whereHas('courseLevel.courseProgram', function ($programQuery) use ($program) {
                    $programQuery->where('slug', $program);
                });
            })
            ->latest('enrolled_at')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.course-access.index', [
            'enrollments' => $enrollments,
            'programs' => $programs,
            'search' => $search,
            'status' => $status,
            'source' => $source,
            'program' => $program,
            'totalAccess' => StudentCourseEnrollment::query()->count(),
            'activeAccess' => StudentCourseEnrollment::query()->where('status', 'active')->count(),
            'completedAccess' => StudentCourseEnrollment::query()->where('status', 'completed')->count(),
            'manualAccess' => StudentCourseEnrollment::query()->where('enrollment_source', 'manual')->count(),
        ]);
    }

    public function create(): View
    {
        $students = User::query()
            ->where('role', 'student')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $courseLevels = CourseLevel::query()
            ->with('courseProgram')
            ->where('is_active', true)
            ->whereHas('courseProgram', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.admin.course-access.create', [
            'students' => $students,
            'courseLevels' => $courseLevels,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'course_level_id' => ['required', 'exists:course_levels,id'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $student = User::query()->findOrFail($validated['student_id']);
        $courseLevel = CourseLevel::query()->findOrFail($validated['course_level_id']);

        if (! $student->isStudent()) {
            throw ValidationException::withMessages([
                'student_id' => 'Selected user must be a student.',
            ]);
        }

        if (! $student->isActive()) {
            throw ValidationException::withMessages([
                'student_id' => 'Selected student account is inactive.',
            ]);
        }

        if (! $courseLevel->is_active) {
            throw ValidationException::withMessages([
                'course_level_id' => 'Selected course is inactive.',
            ]);
        }

        $hasActiveOrCompletedEnrollment = StudentCourseEnrollment::query()
            ->where('student_id', $student->id)
            ->where('course_level_id', $courseLevel->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        if ($hasActiveOrCompletedEnrollment) {
            throw ValidationException::withMessages([
                'course_level_id' => 'This student already has active or completed access to this course.',
            ]);
        }

        $hasPendingOrder = Order::query()
            ->where('student_id', $student->id)
            ->where('course_level_id', $courseLevel->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingOrder) {
            throw ValidationException::withMessages([
                'course_level_id' => 'This student has a pending order for this course. Please approve/reject the order instead.',
            ]);
        }

        $enrollment = DB::transaction(function () use ($student, $courseLevel, $validated) {
            return StudentCourseEnrollment::create([
                'student_id' => $student->id,
                'course_level_id' => $courseLevel->id,
                'order_id' => null,
                'enrollment_source' => 'manual',
                'created_by' => auth()->id(),
                'status' => 'active',
                'progress_percentage' => 0,
                'enrolled_at' => now(),
                'completed_at' => null,
                'expired_at' => $this->calculateExpiredAt($courseLevel),
                'note' => $validated['note'] ?: 'Manually granted by admin.',
            ]);
        });

        return redirect()
            ->route('admin.course-access.show', $enrollment)
            ->with('success', 'Course access has been granted successfully.');
    }

    public function show(StudentCourseEnrollment $enrollment): View
    {
        $enrollment->load([
            'student.studentProfile',
            'courseLevel.courseProgram',
            'courseLevel.modules' => function ($query) {
                $query
                    ->with([
                        'materials',
                        'practices',
                    ])
                    ->orderBy('sort_order')
                    ->orderBy('title');
            },
            'courseLevel.finalExams',
            'order',
            'createdBy',
            'moduleProgress.module',
            'certificate',
        ]);

        $student = $enrollment->student;
        $courseLevel = $enrollment->courseLevel;

        abort_unless($student && $courseLevel, 404);

        $moduleProgress = $enrollment->moduleProgress
            ->keyBy('module_id');

        $modules = $courseLevel->modules
            ->map(function ($module) use ($moduleProgress) {
                $progress = $moduleProgress->get($module->id);

                return [
                    'module' => $module,
                    'progress' => $progress,
                    'status' => $progress?->status ?? 'not_started',
                    'progressPercentage' => $progress?->progress_percentage ?? 0,
                    'startedAt' => $progress?->started_at,
                    'completedAt' => $progress?->completed_at,
                ];
            });

        $practiceAttempts = ModulePracticeAttempt::query()
            ->with('practice.module')
            ->where('student_id', $student->id)
            ->whereHas('practice.module', function ($query) use ($courseLevel) {
                $query->where('course_level_id', $courseLevel->id);
            })
            ->latest('submitted_at')
            ->latest()
            ->get();

        $finalExamAttempts = FinalExamAttempt::query()
            ->with('finalExam.courseLevel')
            ->where('student_id', $student->id)
            ->whereHas('finalExam', function ($query) use ($courseLevel) {
                $query->where('course_level_id', $courseLevel->id);
            })
            ->latest('submitted_at')
            ->latest()
            ->get();

        $certificate = Certificate::query()
            ->where('enrollment_id', $enrollment->id)
            ->with('courseLevel.courseProgram')
            ->latest()
            ->first();

        return view('pages.admin.course-access.show', [
            'enrollment' => $enrollment,
            'student' => $student,
            'profile' => $student->studentProfile,
            'courseLevel' => $courseLevel,
            'courseProgram' => $courseLevel->courseProgram,
            'modules' => $modules,
            'practiceAttempts' => $practiceAttempts,
            'finalExamAttempts' => $finalExamAttempts,
            'certificate' => $certificate,
            'canCancel' => $this->canCancel($enrollment, $certificate),
        ]);
    }

    public function cancel(Request $request, StudentCourseEnrollment $enrollment): RedirectResponse
    {
        $validated = $request->validate([
            'cancel_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $certificate = Certificate::query()
            ->where('enrollment_id', $enrollment->id)
            ->first();

        if (! $this->canCancel($enrollment, $certificate)) {
            return redirect()
                ->route('admin.course-access.show', $enrollment)
                ->with('error', 'This course access cannot be cancelled.');
        }

        $oldNote = trim((string) $enrollment->note);
        $cancelNote = trim((string) ($validated['cancel_note'] ?? ''));

        $noteLines = collect([
            $oldNote ?: null,
            'Cancelled by admin on ' . now()->format('d M Y H:i') . '.',
            $cancelNote ? 'Cancel reason: ' . $cancelNote : null,
        ])
            ->filter()
            ->implode("\n");

        $enrollment->update([
            'status' => 'cancelled',
            'expired_at' => now(),
            'note' => $noteLines,
        ]);

        return redirect()
            ->route('admin.course-access.show', $enrollment)
            ->with('success', 'Course access has been cancelled successfully.');
    }

    private function calculateExpiredAt(CourseLevel $courseLevel)
    {
        if (
            $courseLevel->access_type === 'limited'
            && $courseLevel->access_duration_days
        ) {
            return now()->addDays((int) $courseLevel->access_duration_days);
        }

        return null;
    }

    private function canCancel(StudentCourseEnrollment $enrollment, ?Certificate $certificate): bool
    {
        return $enrollment->status === 'active'
            && ! $certificate;
    }
}
