<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Testimonial;
use App\Services\CertificateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\AdminNotificationService;

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

        return view('pages.student.testimoni', [
            'student' => $student,
            'eligibleCertificates' => $eligibleCertificates,
            'testimonials' => $testimonials,
        ]);
    }

    public function store(
        Request $request,
        CertificateService $certificateService,
        AdminNotificationService $adminNotificationService
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
            return redirect()
                ->route('student.certificates.show', $unlockedCertificate)
                ->with('success', 'Thank you for your testimonial. Your certificate has been unlocked.');
        }

        return redirect()
            ->route('student.my-courses')
            ->with('success', 'Thank you for your testimonial.');
    }
}
