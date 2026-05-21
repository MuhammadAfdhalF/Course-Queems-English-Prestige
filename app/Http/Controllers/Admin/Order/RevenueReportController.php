<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\CourseProgram;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RevenueReportController extends Controller
{
    public function index(Request $request): View
    {
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $method = $request->query('method', 'all');
        $program = $request->query('program');

        $programs = CourseProgram::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $baseQuery = Payment::query()
            ->with(['courseLevel.courseProgram'])
            ->where('payment_status', 'paid')
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('payment_date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('payment_date', '<=', $dateTo);
            })
            ->when(in_array($method, ['manual_transfer', 'cash', 'other'], true), function ($query) use ($method) {
                $query->where('payment_method', $method);
            })
            ->when($program, function ($query) use ($program) {
                $query->whereHas('courseLevel.courseProgram', function ($programQuery) use ($program) {
                    $programQuery->where('slug', $program);
                });
            });

        $payments = (clone $baseQuery)
            ->latest('payment_date')
            ->get();

        $totalRevenue = (clone $baseQuery)->sum('amount');

        $todayRevenue = (clone $baseQuery)
            ->whereDate('payment_date', today())
            ->sum('amount');

        $thisMonthRevenue = (clone $baseQuery)
            ->whereYear('payment_date', now()->year)
            ->whereMonth('payment_date', now()->month)
            ->sum('amount');

        $revenueByMethod = $payments
            ->groupBy('payment_method')
            ->map(fn($items) => $items->sum('amount'));

        $revenueByCourse = $payments
            ->groupBy(fn($payment) => $payment->courseLevel?->name ?? 'Unknown Course')
            ->map(fn($items) => $items->sum('amount'))
            ->sortDesc()
            ->take(8);

        $monthlyRevenue = collect(range(1, 12))
            ->map(function (int $month) {
                $total = Payment::query()
                    ->where('payment_status', 'paid')
                    ->whereYear('payment_date', now()->year)
                    ->whereMonth('payment_date', $month)
                    ->sum('amount');

                return [
                    'month' => now()->setMonth($month)->format('M'),
                    'total' => (float) $total,
                ];
            });

        $maxMonthlyRevenue = max(1, $monthlyRevenue->max('total'));

        $monthlyRevenue = $monthlyRevenue
            ->map(function (array $item) use ($maxMonthlyRevenue) {
                return array_merge($item, [
                    'height' => $item['total'] > 0
                        ? max(8, round(($item['total'] / $maxMonthlyRevenue) * 100))
                        : 6,
                ]);
            });

        return view('pages.admin.revenue.index', [
            'programs' => $programs,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'method' => $method,
            'program' => $program,
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue,
            'thisMonthRevenue' => $thisMonthRevenue,
            'paymentCount' => $payments->count(),
            'revenueByMethod' => $revenueByMethod,
            'revenueByCourse' => $revenueByCourse,
            'monthlyRevenue' => $monthlyRevenue,
        ]);
    }
}
