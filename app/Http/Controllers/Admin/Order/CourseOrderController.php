<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\StudentCourseEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Notification;
use App\Models\User;

class CourseOrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::query()
            ->with([
                'student.studentProfile',
                'courseLevel.courseProgram',
                'payment',
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
                    'rawPrice' => (float) $order->price,
                    'status' => $order->status,
                    'statusLabel' => $this->statusLabel($order->status),
                    'orderDate' => $order->order_date?->format('M d, Y H:i') ?? $order->created_at?->format('M d, Y H:i') ?? '-',
                    'approvedAt' => $order->approved_at?->format('M d, Y H:i'),
                    'rejectedAt' => $order->rejected_at?->format('M d, Y H:i'),
                    'whatsapp' => $order->student?->studentProfile?->whatsapp ?? '-',
                    'note' => $order->note,
                    'paymentStatus' => $order->payment?->payment_status,
                    'paymentAmount' => $order->payment
                        ? 'Rp ' . number_format((float) $order->payment->amount, 0, ',', '.')
                        : null,
                    'paymentDate' => $order->payment?->payment_date?->format('M d, Y H:i'),
                    'detailUrl' => route('admin.orders.show', $order),
                    'paymentUrl' => route('admin.orders.payment', $order),
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

    public function show(Order $order): View
    {
        $order->load([
            'student.studentProfile',
            'courseLevel.courseProgram',
            'payment.confirmedBy',
            'enrollment.createdBy',
        ]);

        $timeline = collect([
            [
                'title' => 'Order Created',
                'description' => 'Student submitted course order.',
                'date' => $order->order_date ?? $order->created_at,
                'variant' => 'default',
            ],
            [
                'title' => 'Payment Recorded',
                'description' => $order->payment
                    ? 'Payment was recorded by admin.'
                    : 'Payment has not been recorded yet.',
                'date' => $order->payment?->payment_date,
                'variant' => 'payment',
            ],
            [
                'title' => 'Order Approved',
                'description' => 'Order approved after payment confirmation.',
                'date' => $order->approved_at,
                'variant' => 'approved',
            ],
            [
                'title' => 'Order Rejected',
                'description' => 'Order was rejected by admin.',
                'date' => $order->rejected_at,
                'variant' => 'rejected',
            ],
            [
                'title' => 'Course Access Created',
                'description' => 'Student enrollment/course access was created.',
                'date' => $order->enrollment?->enrolled_at,
                'variant' => 'access',
            ],
        ])
            ->filter(fn(array $item) => ! is_null($item['date']))
            ->sortBy('date')
            ->values()
            ->all();

        return view('pages.admin.orders.show', [
            'order' => $order,
            'student' => $order->student,
            'profile' => $order->student?->studentProfile,
            'courseLevel' => $order->courseLevel,
            'courseProgram' => $order->courseLevel?->courseProgram,
            'payment' => $order->payment,
            'enrollment' => $order->enrollment,
            'timeline' => $timeline,
        ]);
    }

    public function payment(Order $order): View|RedirectResponse
    {
        $order->load([
            'student.studentProfile',
            'courseLevel.courseProgram',
            'payment',
        ]);

        if ($order->status !== 'pending') {
            return redirect()
                ->route('admin.orders.index')
                ->with('error', 'Only pending orders can record payment.');
        }

        return view('pages.admin.orders.payment', [
            'order' => $order,
            'student' => $order->student,
            'courseLevel' => $order->courseLevel,
            'courseProgram' => $order->courseLevel?->courseProgram,
            'payment' => $order->payment,
        ]);
    }

    public function recordPayment(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:manual_transfer,cash,other'],
            'payment_date' => ['required', 'date'],
            'proof_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:4096'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($order->status !== 'pending') {
            return redirect()
                ->route('admin.orders.index')
                ->with('error', 'Only pending orders can be approved with payment.');
        }

        DB::transaction(function () use ($request, $order, $validated) {
            $order->load(['courseLevel', 'payment']);

            $proofPath = $order->payment?->proof_file;

            if ($request->hasFile('proof_file')) {
                if ($proofPath && Storage::disk('public')->exists($proofPath)) {
                    Storage::disk('public')->delete($proofPath);
                }

                $proofPath = $request->file('proof_file')->store('payments/proofs', 'public');
            }

            $payment = Payment::updateOrCreate(
                [
                    'order_id' => $order->id,
                ],
                [
                    'student_id' => $order->student_id,
                    'course_level_id' => $order->course_level_id,
                    'confirmed_by' => auth()->id(),
                    'amount' => $validated['amount'],
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'paid',
                    'proof_file' => $proofPath,
                    'payment_date' => $validated['payment_date'],
                    'note' => $validated['note'] ?? null,
                ]
            );

            $order->update([
                'status' => 'approved',
                'approved_at' => now(),
                'rejected_at' => null,
                'note' => $validated['note'] ?? $order->note,
            ]);

            $this->createOrUpdateEnrollment($order);
            $this->notifyActiveAdmins(
                title: 'Payment Recorded',
                message: 'Payment for order ' . $order->order_code . ' has been recorded and approved.',
                type: 'payment',
                referenceId: $payment->id,
                referenceType: 'payment'
            );
        });

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Payment has been recorded, order approved, and course access created.');
    }

    public function approve(Request $request, Order $order): RedirectResponse
    {
        if ($order->status !== 'pending') {
            return redirect()
                ->route('admin.orders.index')
                ->with('error', 'Only pending orders can be approved.');
        }

        return redirect()
            ->route('admin.orders.payment', $order)
            ->with('success', 'Please record payment before approving this order.');
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
        $this->notifyActiveAdmins(
            title: 'Order Rejected',
            message: 'Order ' . $order->order_code . ' has been rejected.',
            type: 'order',
            referenceId: $order->id,
            referenceType: 'order'
        );

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order has been rejected.');
    }

    private function createOrUpdateEnrollment(Order $order): void
    {
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
                'note' => 'Created from paid order ' . $order->order_code,
            ]
        );
    }

    private function notifyActiveAdmins(
        string $title,
        string $message,
        string $type,
        int $referenceId,
        string $referenceType
    ): void {
        User::query()
            ->where('role', 'admin')
            ->where('is_active', true)
            ->get()
            ->each(function (User $admin) use ($title, $message, $type, $referenceId, $referenceType) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => $title,
                    'message' => $message,
                    'type' => $type,
                    'reference_id' => $referenceId,
                    'reference_type' => $referenceType,
                    'is_read' => false,
                    'read_at' => null,
                ]);
            });
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
