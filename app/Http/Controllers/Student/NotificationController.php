<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\FinalExamAttempt;
use App\Models\ModulePracticeAttempt;
use App\Models\Notification;
use App\Models\StudentCourseEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');

        if (! in_array($status, ['all', 'unread', 'read'], true)) {
            $status = 'all';
        }

        $baseQuery = Notification::query()
            ->where('user_id', auth()->id());

        $notifications = (clone $baseQuery)
            ->when($status === 'unread', function ($query) {
                $query->where('is_read', false);
            })
            ->when($status === 'read', function ($query) {
                $query->where('is_read', true);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('pages.student.notifications.index', [
            'notifications' => $notifications,
            'status' => $status,
            'totalCount' => (clone $baseQuery)->count(),
            'unreadCount' => (clone $baseQuery)->where('is_read', false)->count(),
            'readCount' => (clone $baseQuery)->where('is_read', true)->count(),
        ]);
    }

    public function open(Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $this->markNotificationAsRead($notification);

        return redirect($this->targetUrl($notification));
    }

    public function markAsRead(Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $this->markNotificationAsRead($notification);

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        Notification::query()
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }

    private function markNotificationAsRead(Notification $notification): void
    {
        if (! $notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    private function targetUrl(Notification $notification): string
    {
        if ($notification->reference_type === 'practice_attempt' && $notification->reference_id) {
            $attempt = ModulePracticeAttempt::query()
                ->with('practice.module')
                ->where('student_id', auth()->id())
                ->find($notification->reference_id);

            $module = $attempt?->practice?->module;

            if ($attempt && $module) {
                $enrollment = StudentCourseEnrollment::query()
                    ->where('student_id', auth()->id())
                    ->where('course_level_id', $module->course_level_id)
                    ->whereIn('status', ['active', 'completed'])
                    ->latest('enrolled_at')
                    ->latest()
                    ->first();

                if ($enrollment) {
                    return route('student.module-practice-result', [
                        'enrollment' => $enrollment,
                        'module' => $module,
                        'attempt' => $attempt,
                    ]);
                }
            }
        }

        if ($notification->reference_type === 'final_exam_attempt' && $notification->reference_id) {
            $attempt = FinalExamAttempt::query()
                ->with('finalExam')
                ->where('student_id', auth()->id())
                ->find($notification->reference_id);

            $finalExam = $attempt?->finalExam;

            if ($attempt && $finalExam) {
                $enrollment = StudentCourseEnrollment::query()
                    ->where('student_id', auth()->id())
                    ->where('course_level_id', $finalExam->course_level_id)
                    ->whereIn('status', ['active', 'completed'])
                    ->latest('enrolled_at')
                    ->latest()
                    ->first();

                if ($enrollment) {
                    return route('student.final-exam-result', [
                        'enrollment' => $enrollment,
                        'attempt' => $attempt,
                    ]);
                }
            }
        }

        if ($notification->reference_type === 'certificate' && $notification->reference_id) {
            $certificate = Certificate::query()
                ->where('student_id', auth()->id())
                ->find($notification->reference_id);

            if ($certificate) {
                return route('student.certificates.show', $certificate);
            }
        }

        if ($notification->reference_type === 'enrollment' && $notification->reference_id) {
            $enrollment = StudentCourseEnrollment::query()
                ->where('student_id', auth()->id())
                ->find($notification->reference_id);

            if ($enrollment) {
                if ($notification->type === 'access_cancelled') {
                    return route('student.my-courses');
                }

                if (in_array($enrollment->status, ['active', 'completed'], true)) {
                    return route('student.learning-path', $enrollment);
                }

                return route('student.my-courses');
            }
        }

        if ($notification->reference_type === 'order' && $notification->reference_id) {
            return route('student.dashboard');
        }

        return route('student.notifications.index');
    }
}
