<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\FinalExamAttempt;
use App\Models\ModulePracticeAttempt;
use App\Models\Notification;

class StudentNotificationService
{
    public function practiceReviewed(ModulePracticeAttempt $attempt): void
    {
        $attempt->loadMissing([
            'student',
            'practice.module.courseLevel.courseProgram',
        ]);

        if (! $attempt->student_id) {
            return;
        }

        $practiceTitle = $attempt->practice?->title ?? 'Your practice';
        $score = number_format((float) $attempt->total_score, 2);

        Notification::create([
            'user_id' => $attempt->student_id,
            'title' => 'Your practice has been reviewed',
            'message' => "{$practiceTitle} has been reviewed. Your score is {$score}%.",
            'type' => 'practice_reviewed',
            'reference_type' => 'practice_attempt',
            'reference_id' => $attempt->id,
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    public function finalExamReviewed(FinalExamAttempt $attempt): void
    {
        $attempt->loadMissing([
            'student',
            'finalExam.courseLevel.courseProgram',
        ]);

        if (! $attempt->student_id) {
            return;
        }

        $examTitle = $attempt->finalExam?->title ?? 'Your final exam';
        $score = number_format((float) $attempt->total_score, 2);

        $message = "{$examTitle} has been reviewed. Your score is {$score}%.";

        if ($attempt->status === 'passed') {
            $message = "Congratulations! {$examTitle} has been reviewed and you passed with {$score}%. Submit your testimonial to unlock your certificate.";
        }

        Notification::create([
            'user_id' => $attempt->student_id,
            'title' => 'Your final exam has been reviewed',
            'message' => $message,
            'type' => 'final_exam_reviewed',
            'reference_type' => 'final_exam_attempt',
            'reference_id' => $attempt->id,
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    public function certificateReady(Certificate $certificate): void
    {
        $certificate->loadMissing([
            'student',
            'courseLevel.courseProgram',
        ]);

        if (! $certificate->student_id) {
            return;
        }

        $courseName = $certificate->courseLevel?->name ?? 'your course';

        Notification::create([
            'user_id' => $certificate->student_id,
            'title' => 'Your certificate is ready',
            'message' => "Your certificate for {$courseName} is now available to view and download.",
            'type' => 'certificate_ready',
            'reference_type' => 'certificate',
            'reference_id' => $certificate->id,
            'is_read' => false,
            'read_at' => null,
        ]);
    }
}