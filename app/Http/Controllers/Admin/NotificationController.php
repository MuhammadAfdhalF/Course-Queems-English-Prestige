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
    public function index(): View
    {
        $notifications = Notification::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        $unreadCount = Notification::query()
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return view('pages.admin.notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
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
