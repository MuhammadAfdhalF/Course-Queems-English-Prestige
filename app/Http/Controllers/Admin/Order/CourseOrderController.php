<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StudentCourseEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CourseOrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::query()
            ->with([
                'student.studentProfile',
                'courseLevel.courseProgram',
            ])
            ->latest('order_date')
            ->latest()
            ->get()
            ->map(function (Order $order) {
                return [
                    'databaseId' => $order->id,
                    'id' => $order->order_code,
                    'studentName' => $order->student?->name ?? 'Unknown Student',
                    'studentEmail' => $order->student?->email ?? '-',
                    'studentInitials' => $this->makeInitials($order->student?->name ?? 'Unknown Student'),
                    'avatarColor' => $this->avatarColor($order->id),
                    'course' => $order->courseLevel?->name ?? 'Unknown Course',
                    'program' => $order->courseLevel?->courseProgram?->name ?? 'Course Program',
                    'price' => 'Rp ' . number_format((float) $order->price, 0, ',', '.'),
                    'status' => $order->status,
                    'statusLabel' => $this->statusLabel($order->status),
                    'orderDate' => $order->order_date?->format('M d, Y H:i') ?? $order->created_at?->format('M d, Y H:i') ?? '-',
                    'approvedAt' => $order->approved_at?->format('M d, Y H:i'),
                    'rejectedAt' => $order->rejected_at?->format('M d, Y H:i'),
                    'whatsapp' => $order->student?->studentProfile?->whatsapp ?? '-',
                    'note' => $order->note,
                    'approveUrl' => route('admin.orders.approve', $order),
                    'rejectUrl' => route('admin.orders.reject', $order),
                ];
            })
            ->values()
            ->all();

        $tabs = [
            [
                'key' => 'pending',
                'label' => 'Pending',
                'count' => Order::query()->where('status', 'pending')->count(),
            ],
            [
                'key' => 'approved',
                'label' => 'Approved',
                'count' => Order::query()->where('status', 'approved')->count(),
            ],
            [
                'key' => 'rejected',
                'label' => 'Rejected',
                'count' => Order::query()->where('status', 'rejected')->count(),
            ],
            [
                'key' => 'cancelled',
                'label' => 'Cancelled',
                'count' => Order::query()->where('status', 'cancelled')->count(),
            ],
        ];

        return view('pages.admin.orders.index', [
            'orders' => $orders,
            'tabs' => $tabs,
            'orderCount' => count($orders),
        ]);
    }

    public function approve(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($order->status !== 'pending') {
            return redirect()
                ->route('admin.orders.index')
                ->with('error', 'Only pending orders can be approved.');
        }

        DB::transaction(function () use ($order, $validated) {
            $order->load('courseLevel');

            $order->update([
                'status' => 'approved',
                'approved_at' => now(),
                'rejected_at' => null,
                'note' => $validated['note'] ?? $order->note,
            ]);

            $expiredAt = null;

            if (
                $order->courseLevel?->access_type === 'limited'
                && $order->courseLevel?->access_duration_days
            ) {
                $expiredAt = now()->addDays((int) $order->courseLevel->access_duration_days);
            }

            StudentCourseEnrollment::updateOrCreate(
                [
                    'student_id' => $order->student_id,
                    'course_level_id' => $order->course_level_id,
                ],
                [
                    'order_id' => $order->id,
                    'enrollment_source' => 'order',
                    'created_by' => auth()->id(),
                    'status' => 'active',
                    'progress_percentage' => 0,
                    'enrolled_at' => now(),
                    'completed_at' => null,
                    'expired_at' => $expiredAt,
                    'note' => 'Created from approved order ' . $order->order_code,
                ]
            );
        });

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order has been approved and course access has been created.');
    }

    public function reject(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($order->status !== 'pending') {
            return redirect()
                ->route('admin.orders.index')
                ->with('error', 'Only pending orders can be rejected.');
        }

        $order->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'approved_at' => null,
            'note' => $validated['note'] ?? $order->note,
        ]);

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order has been rejected.');
    }

    private function makeInitials(string $name): string
    {
        $words = collect(explode(' ', trim($name)))
            ->filter()
            ->values();

        if ($words->isEmpty()) {
            return 'ST';
        }

        return $words
            ->take(2)
            ->map(fn(string $word) => strtoupper(substr($word, 0, 1)))
            ->implode('');
    }

    private function avatarColor(int $id): string
    {
        $colors = [
            'bg-blue-100 text-blue-700',
            'bg-purple-100 text-purple-700',
            'bg-yellow-100 text-yellow-700',
            'bg-emerald-100 text-emerald-700',
            'bg-rose-100 text-rose-700',
            'bg-slate-100 text-slate-700',
        ];

        return $colors[$id % count($colors)];
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            default => 'Pending',
        };
    }
}
