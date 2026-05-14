<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function show(Certificate $certificate): View|RedirectResponse
    {
        abort_unless($certificate->student_id === auth()->id(), 403);

        if ($certificate->status === 'locked') {
            return redirect()
                ->route('student.testimoni')
                ->with('info', 'Please submit your testimonial to unlock this certificate.');
        }

        abort_unless($certificate->status === 'issued', 403, 'This certificate is not available.');

        $certificate->load([
            'student',
            'courseLevel.courseProgram',
            'enrollment',
            'finalExamAttempt.finalExam',
            'certificateTemplate',
        ]);

        return view('pages.student.certificate-show', [
            'certificate' => $certificate,
            'student' => $certificate->student,
            'courseLevel' => $certificate->courseLevel,
            'courseProgram' => $certificate->courseLevel?->courseProgram,
            'finalExamAttempt' => $certificate->finalExamAttempt,
        ]);
    }
}
