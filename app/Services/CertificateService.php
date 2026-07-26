<?php

namespace App\Services;

use App\Exceptions\InconsistentEnrollmentStateException;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\CourseLevel;
use App\Models\FinalExamAttempt;
use App\Models\StudentCourseEnrollment;
use App\Models\Testimonial;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CertificateService
{
    public function evaluateAndCreateForEnrollment(StudentCourseEnrollment $enrollment): ?Certificate
    {
        $maxAttempts = 3;

        for ($attemptIndex = 1; $attemptIndex <= $maxAttempts; $attemptIndex++) {
            try {
                return DB::transaction(function () use ($enrollment) {
                    $lockedEnrollment = StudentCourseEnrollment::query()
                        ->whereKey($enrollment->id)
                        ->lockForUpdate()
                        ->first();

                    if (! $lockedEnrollment) {
                        return null;
                    }

                    $existingCertificate = Certificate::query()
                        ->where('enrollment_id', $lockedEnrollment->id)
                        ->first();

                    if ($existingCertificate) {
                        if ($lockedEnrollment->status !== 'completed') {
                            Log::warning("Syncing completed status for active enrollment #{$lockedEnrollment->id} with existing certificate #{$existingCertificate->id}");
                            $lockedEnrollment->update([
                                'status' => 'completed',
                                'progress_percentage' => 100,
                                'completed_at' => $lockedEnrollment->completed_at ?? $existingCertificate->created_at ?? now(),
                            ]);
                        }

                        return $existingCertificate;
                    }

                    if ($lockedEnrollment->status === 'completed') {
                        Log::critical("Inconsistent completion state: Completed enrollment #{$lockedEnrollment->id} missing certificate");
                        throw new InconsistentEnrollmentStateException();
                    }

                    $courseLevel = $lockedEnrollment->courseLevel;
                    if (! $courseLevel) {
                        return null;
                    }

                    $activeSections = $courseLevel->finalExams()
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('id')
                        ->get();

                    if ($activeSections->isEmpty()) {
                        return null;
                    }

                    $sectionSnapshots = [];
                    $totalPercentageScores = 0;

                    foreach ($activeSections as $section) {
                        $activeQuestionsCount = $section->questions()
                            ->where('is_active', true)
                            ->count();

                        if ($activeQuestionsCount === 0) {
                            Log::warning("Active final exam section #{$section->id} has no active questions. Enrollment #{$lockedEnrollment->id} completion blocked.");
                            return null;
                        }

                        $qualifyingAttempt = FinalExamAttempt::query()
                            ->where('student_id', $lockedEnrollment->student_id)
                            ->where('final_exam_id', $section->id)
                            ->whereNotNull('submitted_at')
                            ->whereNotNull('graded_at')
                            ->where(function ($query) {
                                $query->where(function ($sub) {
                                    $sub->where('result_mode', 'pass_fail')
                                        ->where(function ($passQ) {
                                            $passQ->where('is_passed', true)
                                                 ->orWhere('status', 'passed');
                                        });
                                })->orWhere('result_mode', 'score_only');
                            })
                            ->orderByDesc('percentage_score')
                            ->orderByDesc('raw_score')
                            ->orderByDesc('graded_at')
                            ->orderByDesc('id')
                            ->first();

                        if (! $qualifyingAttempt) {
                            return null;
                        }

                        $rawResultMode = $qualifyingAttempt->result_mode;
                        $resultModeStr = $rawResultMode instanceof \App\Enums\AssessmentResultMode
                            ? $rawResultMode->value
                            : (string) $rawResultMode;

                        $sectionSnapshots[] = [
                            'final_exam_id' => $section->id,
                            'section_id' => $section->id,
                            'title' => $section->title,
                            'section_title' => $section->title,
                            'sort_order' => (int) ($section->sort_order ?? 1),
                            'attempt_id' => $qualifyingAttempt->id,
                            'raw_score' => round((float) $qualifyingAttempt->raw_score, 2),
                            'max_score' => round((float) $qualifyingAttempt->max_score, 2),
                            'percentage_score' => round((float) $qualifyingAttempt->percentage_score, 2),
                            'score' => round((float) $qualifyingAttempt->percentage_score, 2),
                            'result_mode' => $resultModeStr,
                            'passing_score' => ($resultModeStr === 'pass_fail' && $qualifyingAttempt->passing_score !== null)
                                ? round((float) $qualifyingAttempt->passing_score, 2)
                                : null,
                            'is_passed' => ($resultModeStr === 'pass_fail') ? (bool) $qualifyingAttempt->is_passed : null,
                            'graded_at' => $qualifyingAttempt->graded_at?->toIso8601String(),
                        ];

                        $totalPercentageScores += (float) $qualifyingAttempt->percentage_score;
                    }

                    $finalScore = round($totalPercentageScores / count($activeSections), 2);
                    $lastAttemptId = end($sectionSnapshots)['attempt_id'];

                    $certificate = Certificate::create([
                        'student_id' => $lockedEnrollment->student_id,
                        'course_level_id' => $lockedEnrollment->course_level_id,
                        'enrollment_id' => $lockedEnrollment->id,
                        'final_exam_attempt_id' => $lastAttemptId,
                        'certificate_template_id' => $this->resolveTemplate($courseLevel)->id,
                        'certificate_number' => $this->generateCertificateNumber(),
                        'verification_token' => $this->generateVerificationToken(),
                        'certificate_file' => null,
                        'issued_at' => null,
                        'status' => 'locked',
                        'section_scores' => $sectionSnapshots,
                        'final_score' => $finalScore,
                    ]);

                    $lockedEnrollment->update([
                        'status' => 'completed',
                        'progress_percentage' => 100,
                        'completed_at' => $lockedEnrollment->completed_at ?? now(),
                    ]);

                    return $certificate;
                });
            } catch (QueryException $e) {
                if (! $this->isRetryableCertificateCollision($e) || $attemptIndex === $maxAttempts) {
                    throw $e;
                }
            }
        }

        return null;
    }

    public function createLockedCertificateFromAttempt(FinalExamAttempt $attempt): ?Certificate
    {
        $attempt->loadMissing('finalExam');
        $courseLevelId = $attempt->finalExam?->course_level_id;

        if (! $courseLevelId) {
            return null;
        }

        $enrollment = StudentCourseEnrollment::query()
            ->where('student_id', $attempt->student_id)
            ->where('course_level_id', $courseLevelId)
            ->whereIn('status', ['active', 'completed'])
            ->latest('enrolled_at')
            ->latest()
            ->first();

        if (! $enrollment) {
            return null;
        }

        return $this->evaluateAndCreateForEnrollment($enrollment);
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

    private function isRetryableCertificateCollision(QueryException $exception): bool
    {
        $message = $exception->getMessage();

        return str_contains($message, 'certificates_certificate_number_unique')
            || str_contains($message, 'certificates_verification_token_unique')
            || str_contains($message, 'certificates_enrollment_id_unique')
            || str_contains($message, '1062');
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

    private function generateVerificationToken(): string
    {
        do {
            $token = Str::random(48);
        } while (
            Certificate::query()
            ->where('verification_token', $token)
            ->exists()
        );

        return $token;
    }
}
