<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\StudentCourseEnrollment;
use App\Models\Testimonial;
use App\Services\AdminNotificationService;
use App\Services\CertificateService;
use App\Services\StudentNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();

        $eligibleCertificates = Certificate::query()
            ->with('courseLevel.courseProgram')
            ->where('student_id', $student->id)
            ->where('status', 'locked')
            ->latest()
            ->get();

        $testimonials = Testimonial::query()
            ->with('courseLevel.courseProgram')
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        $canSubmitCompanyTestimonial = StudentCourseEnrollment::query()
            ->where('student_id', $student->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        return view('pages.student.testimoni', [
            'student' => $student,
            'eligibleCertificates' => $eligibleCertificates,
            'testimonials' => $testimonials,
            'canSubmitCompanyTestimonial' => $canSubmitCompanyTestimonial,
        ]);
    }

    public function storeCourse(
        Request $request,
        CertificateService $certificateService,
        AdminNotificationService $adminNotificationService,
        StudentNotificationService $studentNotificationService
    ): RedirectResponse {
        $validated = $request->validate([
            'certificate_id' => ['required', 'integer', 'exists:certificates,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'testimonial' => ['required', 'string', 'min:10'],
        ]);

        $certificate = Certificate::query()
            ->with('courseLevel')
            ->where('id', $validated['certificate_id'])
            ->where('student_id', auth()->id())
            ->where('status', 'locked')
            ->firstOrFail();

        $testimonial = Testimonial::create([
            'student_id' => auth()->id(),
            'course_level_id' => $certificate->course_level_id,
            'name' => auth()->user()->name,
            'photo' => null,
            'rating' => $validated['rating'],
            'testimonial' => $validated['testimonial'],
            'type' => 'course',
            'is_featured' => false,
            'is_active' => false,
        ]);

        $adminNotificationService->testimonialSubmitted($testimonial->fresh());

        $unlockedCertificate = $certificateService->unlockCertificateFromTestimonial($testimonial);

        if ($unlockedCertificate) {
            $studentNotificationService->certificateReady($unlockedCertificate->fresh());

            return redirect()
                ->route('student.certificates.show', $unlockedCertificate)
                ->with('success', 'Thank you for your course testimonial. Your certificate has been unlocked.');
        }

        return redirect()
            ->route('student.my-courses')
            ->with('success', 'Thank you for your course testimonial.');
    }

    public function storeCompany(
        Request $request,
        AdminNotificationService $adminNotificationService
    ): RedirectResponse {
        $validated = $request->validate([
            'company_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'company_testimonial' => ['required', 'string', 'min:10'],
        ]);

        $canSubmitCompanyTestimonial = StudentCourseEnrollment::query()
            ->where('student_id', auth()->id())
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        if (! $canSubmitCompanyTestimonial) {
            return redirect()
                ->route('student.testimoni')
                ->with('error', 'You need an active or completed course before submitting a general testimonial.');
        }

        $testimonial = Testimonial::create([
            'student_id' => auth()->id(),
            'course_level_id' => null,
            'name' => auth()->user()->name,
            'photo' => null,
            'rating' => $validated['company_rating'],
            'testimonial' => $validated['company_testimonial'],
            'type' => 'company',
            'is_featured' => false,
            'is_active' => false,
        ]);

        $adminNotificationService->testimonialSubmitted($testimonial->fresh());

        return redirect()
            ->route('student.testimoni')
            ->with('success', 'Thank you for sharing your experience with Queens English Prestige.');
    }
}
