<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\CertificateSetting;

class CertificateController extends Controller
{
    public function index(): View
    {
        $certificates = Certificate::query()
            ->with([
                'student',
                'courseLevel.courseProgram',
                'enrollment',
                'finalExamAttempt.finalExam',
                'certificateTemplate',
            ])
            ->latest()
            ->get();

        return view('pages.admin.course-management.certificates.index', [
            'certificates' => $certificates,
            'lockedCount' => $certificates->where('status', 'locked')->count(),
            'issuedCount' => $certificates->where('status', 'issued')->count(),
            'revokedCount' => $certificates->where('status', 'revoked')->count(),
        ]);
    }

    public function show(Certificate $certificate): View
    {
        $certificate->load([
            'student',
            'courseLevel.courseProgram',
            'enrollment',
            'finalExamAttempt.finalExam',
            'certificateTemplate',
        ]);

        return view('pages.admin.course-management.certificates.show', [
            'certificate' => $certificate,
            'student' => $certificate->student,
            'courseLevel' => $certificate->courseLevel,
            'courseProgram' => $certificate->courseLevel?->courseProgram,
            'finalExamAttempt' => $certificate->finalExamAttempt,
            'certificateSetting' => CertificateSetting::current(),
        ]);
    }

    public function download(Certificate $certificate)
    {
        abort_unless($certificate->status === 'issued', 403, 'This certificate is not available for download.');

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

    public function revoke(Certificate $certificate): RedirectResponse
    {
        abort_unless($certificate->status === 'issued', 403, 'Only issued certificates can be revoked.');

        if ($certificate->certificate_file && Storage::disk('public')->exists($certificate->certificate_file)) {
            Storage::disk('public')->delete($certificate->certificate_file);
        }

        $certificate->update([
            'status' => 'revoked',
            'certificate_file' => null,
        ]);

        return redirect()
            ->route('admin.course-management.certificates.show', $certificate)
            ->with('success', 'Certificate has been revoked successfully.');
    }

    public function reissue(Certificate $certificate): RedirectResponse
    {
        abort_unless($certificate->status === 'revoked', 403, 'Only revoked certificates can be re-issued.');

        $certificate->update([
            'status' => 'issued',
            'issued_at' => $certificate->issued_at ?? now(),
            'certificate_file' => null,
        ]);

        return redirect()
            ->route('admin.course-management.certificates.show', $certificate)
            ->with('success', 'Certificate has been re-issued successfully.');
    }

    private function generatePdf(Certificate $certificate): string
    {
        $pdf = Pdf::loadView('pdf.certificate', [
            'certificate' => $certificate,
            'student' => $certificate->student,
            'courseLevel' => $certificate->courseLevel,
            'courseProgram' => $certificate->courseLevel?->courseProgram,
            'finalExamAttempt' => $certificate->finalExamAttempt,
            'certificateSetting' => CertificateSetting::current(),
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
