<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use App\Models\Order;
use App\Models\StudentCourseEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CourseOrderController extends Controller
{
    public function create(CourseLevel $courseLevel): View|RedirectResponse
    {
        $courseLevel->load('courseProgram');

        $this->ensureCourseIsAvailable($courseLevel);

        $student = auth()->user();

        if ($this->studentHasActiveEnrollment($student->id, $courseLevel->id)) {
            return redirect()
                ->route('student.my-courses')
                ->with('success', 'You already have access to this course.');
        }

        if ($this->studentHasPendingOrder($student->id, $courseLevel->id)) {
            return redirect()
                ->route('courses.show', $courseLevel)
                ->with('success', 'You already have a pending order for this course. Our admin will contact you soon.');
        }

        $student->load('studentProfile');

        return view('pages.public.course-order', [
            'title' => 'Order Course - Queens English Prestige',
            'courseLevel' => $courseLevel,
            'student' => $student,
        ]);
    }

    public function store(Request $request, CourseLevel $courseLevel): RedirectResponse
    {
        $courseLevel->load('courseProgram');

        $this->ensureCourseIsAvailable($courseLevel);

        $validated = $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $student = $request->user();

        if ($this->studentHasActiveEnrollment($student->id, $courseLevel->id)) {
            return redirect()
                ->route('student.my-courses')
                ->with('success', 'You already have access to this course.');
        }

        if ($this->studentHasPendingOrder($student->id, $courseLevel->id)) {
            return redirect()
                ->route('courses.show', $courseLevel)
                ->with('success', 'You already have a pending order for this course. Our admin will contact you soon.');
        }

        $order = DB::transaction(function () use ($student, $courseLevel, $validated) {
            return Order::create([
                'student_id' => $student->id,
                'course_level_id' => $courseLevel->id,
                'order_code' => $this->generateOrderCode(),
                'price' => $courseLevel->price ?? 0,
                'status' => 'pending',
                'order_date' => now(),
                'note' => $validated['note'] ?? null,
            ]);
        });

        return redirect()
            ->route('courses.show', $courseLevel)
            ->with('success', 'Your order ' . $order->order_code . ' has been submitted. Our admin will contact you via WhatsApp.');
    }

    private function ensureCourseIsAvailable(CourseLevel $courseLevel): void
    {
        abort_unless($courseLevel->is_active, 404);

        abort_if(
            ! $courseLevel->courseProgram || ! $courseLevel->courseProgram->is_active,
            404
        );
    }

    private function studentHasActiveEnrollment(int $studentId, int $courseLevelId): bool
    {
        return StudentCourseEnrollment::query()
            ->where('student_id', $studentId)
            ->where('course_level_id', $courseLevelId)
            ->where('status', 'active')
            ->exists();
    }

    private function studentHasPendingOrder(int $studentId, int $courseLevelId): bool
    {
        return Order::query()
            ->where('student_id', $studentId)
            ->where('course_level_id', $courseLevelId)
            ->where('status', 'pending')
            ->exists();
    }

    private function generateOrderCode(): string
    {
        $date = Carbon::now()->format('Ymd');
        $prefix = 'QEP-' . $date . '-';

        $todayOrderCount = Order::query()
            ->whereDate('created_at', Carbon::today())
            ->count();

        do {
            $sequence = str_pad((string) ($todayOrderCount + 1), 4, '0', STR_PAD_LEFT);
            $orderCode = $prefix . $sequence;
            $todayOrderCount++;
        } while (Order::query()->where('order_code', $orderCode)->exists());

        return $orderCode;
    }
}
