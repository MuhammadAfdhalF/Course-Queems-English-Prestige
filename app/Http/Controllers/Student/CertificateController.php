<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function show(Certificate $certificate): View|RedirectResponse
    {
        $this->authorizeCertificate($certificate);

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

    public function download(Certificate $certificate)
    {
        $this->authorizeCertificate($certificate);

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

        if ($certificate->certificate_file && Storage::disk('public')->exists($certificate->certificate_file)) {
            return Storage::disk('public')->download(
                $certificate->certificate_file,
                $this->downloadFileName($certificate)
            );
        }

        $pdfPath = $this->generatePdf($certificate);

        $certificate->update([
            'certificate_file' => $pdfPath,
        ]);

        return Storage::disk('public')->download(
            $pdfPath,
            $this->downloadFileName($certificate)
        );
    }

    private function authorizeCertificate(Certificate $certificate): void
    {
        abort_unless($certificate->student_id === auth()->id(), 403);
    }

    private function generatePdf(Certificate $certificate): string
    {
        $pdf = Pdf::loadView('pdf.certificate', [
            'certificate' => $certificate,
            'student' => $certificate->student,
            'courseLevel' => $certificate->courseLevel,
            'courseProgram' => $certificate->courseLevel?->courseProgram,
            'finalExamAttempt' => $certificate->finalExamAttempt,
        ])->setPaper('a4', 'landscape');

        $fileName = Str::slug($certificate->certificate_number) . '.pdf';
        $path = 'certificates/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    private function downloadFileName(Certificate $certificate): string
    {
        return Str::slug($certificate->certificate_number . '-' . ($certificate->courseLevel?->name ?? 'certificate')) . '.pdf';
    }
}
