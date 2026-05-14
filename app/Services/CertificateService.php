<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\CourseLevel;
use App\Models\FinalExamAttempt;
use App\Models\StudentCourseEnrollment;
use App\Models\Testimonial;

class CertificateService
{
    public function createLockedCertificateFromAttempt(FinalExamAttempt $attempt): ?Certificate
    {
        $attempt->loadMissing([
            'student',
            'finalExam.courseLevel.courseProgram',
        ]);

        if ($attempt->status !== 'passed') {
            return null;
        }

        $finalExam = $attempt->finalExam;
        $courseLevel = $finalExam?->courseLevel;

        if (! $finalExam || ! $courseLevel) {
            return null;
        }

        $enrollment = StudentCourseEnrollment::query()
            ->where('student_id', $attempt->student_id)
            ->where('course_level_id', $courseLevel->id)
            ->whereIn('status', ['active', 'completed'])
            ->latest('enrolled_at')
            ->latest()
            ->first();

        if (! $enrollment) {
            return null;
        }

        $enrollment->update([
            'status' => 'completed',
            'progress_percentage' => 100,
            'completed_at' => $enrollment->completed_at ?? now(),
        ]);

        $existingCertificate = Certificate::query()
            ->where('enrollment_id', $enrollment->id)
            ->whereIn('status', ['locked', 'issued'])
            ->first();

        if ($existingCertificate) {
            return $existingCertificate;
        }

        return Certificate::create([
            'student_id' => $attempt->student_id,
            'course_level_id' => $courseLevel->id,
            'enrollment_id' => $enrollment->id,
            'final_exam_attempt_id' => $attempt->id,
            'certificate_template_id' => $this->resolveTemplate($courseLevel)->id,
            'certificate_number' => $this->generateCertificateNumber(),
            'certificate_file' => null,
            'issued_at' => null,
            'status' => 'locked',
        ]);
    }

    public function unlockCertificateFromTestimonial(Testimonial $testimonial): ?Certificate
    {
        if (! $testimonial->student_id || ! $testimonial->course_level_id) {
            return null;
        }

        $certificate = Certificate::query()
            ->where('student_id', $testimonial->student_id)
            ->where('course_level_id', $testimonial->course_level_id)
            ->where('status', 'locked')
            ->latest()
            ->first();

        if (! $certificate) {
            return null;
        }

        $certificate->update([
            'status' => 'issued',
            'issued_at' => now(),
        ]);

        return $certificate;
    }

    private function resolveTemplate(CourseLevel $courseLevel): CertificateTemplate
    {
        $courseProgramId = $courseLevel->course_program_id;

        $programTemplate = CertificateTemplate::query()
            ->where('is_active', true)
            ->where('course_program_id', $courseProgramId)
            ->orderByDesc('is_default')
            ->latest()
            ->first();

        if ($programTemplate) {
            return $programTemplate;
        }

        $globalDefaultTemplate = CertificateTemplate::query()
            ->where('is_active', true)
            ->whereNull('course_program_id')
            ->orderByDesc('is_default')
            ->latest()
            ->first();

        if ($globalDefaultTemplate) {
            return $globalDefaultTemplate;
        }

        $anyActiveTemplate = CertificateTemplate::query()
            ->where('is_active', true)
            ->latest()
            ->first();

        if ($anyActiveTemplate) {
            return $anyActiveTemplate;
        }

        return CertificateTemplate::create([
            'course_program_id' => null,
            'name' => 'Default Certificate Template',
            'background_image' => null,
            'is_default' => true,
            'is_active' => true,
        ]);
    }

    private function generateCertificateNumber(): string
    {
        $prefix = 'QEP-CERT-' . now()->format('Y') . '-';

        $sequence = Certificate::query()
            ->where('certificate_number', 'like', $prefix . '%')
            ->count() + 1;

        do {
            $number = $prefix . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
            $sequence++;
        } while (
            Certificate::query()
            ->where('certificate_number', $number)
            ->exists()
        );

        return $number;
    }
}
