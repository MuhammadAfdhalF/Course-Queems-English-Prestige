<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\View\View;

class CertificateVerificationController extends Controller
{
    public function show(string $token): View
    {
        $certificate = Certificate::query()
            ->with([
                'student',
                'courseLevel.courseProgram',
                'finalExamAttempt.finalExam',
            ])
            ->where('verification_token', $token)
            ->first();

        return view('pages.public.certificate-verification.show', [
            'certificate' => $certificate,
            'student' => $certificate?->student,
            'courseLevel' => $certificate?->courseLevel,
            'courseProgram' => $certificate?->courseLevel?->courseProgram,
            'finalExamAttempt' => $certificate?->finalExamAttempt,
        ]);
    }
}
