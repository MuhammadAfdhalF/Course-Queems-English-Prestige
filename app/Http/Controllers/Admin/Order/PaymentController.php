<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $method = $request->query('method', 'all');

        $payments = Payment::query()
            ->with([
                'order',
                'student',
                'courseLevel.courseProgram',
                'confirmedBy',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($paymentQuery) use ($search) {
                    $paymentQuery
                        ->whereHas('order', function ($orderQuery) use ($search) {
                            $orderQuery->where('order_code', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('student', function ($studentQuery) use ($search) {
                            $studentQuery
                                ->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('courseLevel', function ($courseQuery) use ($search) {
                            $courseQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when(in_array($status, ['unpaid', 'paid', 'cancelled'], true), function ($query) use ($status) {
                $query->where('payment_status', $status);
            })
            ->when(in_array($method, ['manual_transfer', 'cash', 'other'], true), function ($query) use ($method) {
                $query->where('payment_method', $method);
            })
            ->latest('payment_date')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.payments.index', [
            'payments' => $payments,
            'search' => $search,
            'status' => $status,
            'method' => $method,
            'totalPaid' => Payment::query()->where('payment_status', 'paid')->sum('amount'),
            'thisMonthPaid' => Payment::query()
                ->where('payment_status', 'paid')
                ->whereYear('payment_date', now()->year)
                ->whereMonth('payment_date', now()->month)
                ->sum('amount'),
            'totalPayments' => Payment::query()->count(),
            'paidPayments' => Payment::query()->where('payment_status', 'paid')->count(),
        ]);
    }

    public function show(Payment $payment): View
    {
        $payment->load([
            'order',
            'student.studentProfile',
            'courseLevel.courseProgram',
            'confirmedBy',
        ]);

        return view('pages.admin.payments.show', [
            'payment' => $payment,
            'order' => $payment->order,
            'student' => $payment->student,
            'courseLevel' => $payment->courseLevel,
            'courseProgram' => $payment->courseLevel?->courseProgram,
            'confirmedBy' => $payment->confirmedBy,
        ]);
    }
}
