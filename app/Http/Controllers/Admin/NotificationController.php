<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\FinalExamAttempt;
use App\Models\ModulePracticeAttempt;
use App\Models\Testimonial;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $type = $request->query('type', 'all');

        $allowedStatuses = ['all', 'unread', 'read'];
        $allowedTypes = [
            'all',
            'order',
            'payment',
            'practice_review',
            'final_exam_review',
            'testimonial',
        ];

        if (! in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        if (! in_array($type, $allowedTypes, true)) {
            $type = 'all';
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
            ->when($type !== 'all', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $totalCount = (clone $baseQuery)->count();

        $unreadCount = (clone $baseQuery)
            ->where('is_read', false)
            ->count();

        $readCount = (clone $baseQuery)
            ->where('is_read', true)
            ->count();

        $typeOptions = [
            'all' => 'All Types',
            'order' => 'Orders',
            'payment' => 'Payments',
            'practice_review' => 'Practice Reviews',
            'final_exam_review' => 'Final Exam Reviews',
            'testimonial' => 'Testimonials',
        ];

        return view('pages.admin.notifications.index', [
            'notifications' => $notifications,
            'status' => $status,
            'type' => $type,
            'typeOptions' => $typeOptions,
            'totalCount' => $totalCount,
            'unreadCount' => $unreadCount,
            'readCount' => $readCount,
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
        if ($notification->reference_type === 'order' && $notification->reference_id) {
            $order = Order::query()->find($notification->reference_id);

            if ($order) {
                return route('admin.orders.show', $order);
            }
        }

        if ($notification->reference_type === 'payment' && $notification->reference_id) {
            $payment = Payment::query()->find($notification->reference_id);

            if ($payment) {
                return route('admin.payments.show', $payment);
            }
        }

        if ($notification->reference_type === 'practice_attempt' && $notification->reference_id) {
            $attempt = ModulePracticeAttempt::query()->find($notification->reference_id);

            if ($attempt) {
                return route('admin.course-management.practice-reviews.show', $attempt);
            }
        }

        if ($notification->reference_type === 'final_exam_attempt' && $notification->reference_id) {
            $attempt = FinalExamAttempt::query()->find($notification->reference_id);

            if ($attempt) {
                return route('admin.course-management.final-exam-reviews.show', $attempt);
            }
        }

        if ($notification->reference_type === 'testimonial' && $notification->reference_id) {
            $testimonial = Testimonial::query()->find($notification->reference_id);

            if ($testimonial) {
                return route('admin.cms.testimonials.index');
            }
        }

        return route('admin.notifications.index');
    }
}
