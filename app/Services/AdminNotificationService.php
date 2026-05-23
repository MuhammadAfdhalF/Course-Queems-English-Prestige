<?php

namespace App\Services;

use App\Models\FinalExamAttempt;
use App\Models\ModulePracticeAttempt;
use App\Models\Notification;
use App\Models\Testimonial;
use App\Models\User;

class AdminNotificationService
{
    public function practiceWaitingReview(ModulePracticeAttempt $attempt): void
    {
        $attempt->loadMissing([
            'student',
            'practice.module.courseLevel.courseProgram',
        ]);

        $studentName = $attempt->student?->name ?? 'A student';
        $practiceTitle = $attempt->practice?->title ?? 'a practice';

        $this->notifyAdmins([
            'title' => 'New Practice Submission',
            'message' => "{$studentName} submitted {$practiceTitle} and it needs review.",
            'type' => 'practice_review',
            'reference_type' => 'practice_attempt',
            'reference_id' => $attempt->id,
        ]);
    }

    public function finalExamWaitingReview(FinalExamAttempt $attempt): void
    {
        $attempt->loadMissing([
            'student',
            'finalExam.courseLevel.courseProgram',
        ]);

        $studentName = $attempt->student?->name ?? 'A student';
        $examTitle = $attempt->finalExam?->title ?? 'a final exam';

        $this->notifyAdmins([
            'title' => 'New Final Exam Submission',
            'message' => "{$studentName} submitted {$examTitle} and it needs review.",
            'type' => 'final_exam_review',
            'reference_type' => 'final_exam_attempt',
            'reference_id' => $attempt->id,
        ]);
    }

    public function testimonialSubmitted(Testimonial $testimonial): void
    {
        $testimonial->loadMissing([
            'student',
            'courseLevel.courseProgram',
        ]);

        $studentName = $testimonial->student?->name ?? $testimonial->name ?? 'A student';
        $courseName = $testimonial->courseLevel?->name ?? 'a course';

        $this->notifyAdmins([
            'title' => 'New Testimonial Submitted',
            'message' => "{$studentName} submitted a testimonial for {$courseName}.",
            'type' => 'testimonial',
            'reference_type' => 'testimonial',
            'reference_id' => $testimonial->id,
        ]);
    }

    private function notifyAdmins(array $payload): void
    {
        $adminIds = User::query()
            ->where('role', 'admin')
            ->where('is_active', true)
            ->pluck('id');

        foreach ($adminIds as $adminId) {
            Notification::create([
                'user_id' => $adminId,
                'title' => $payload['title'],
                'message' => $payload['message'],
                'type' => $payload['type'],
                'reference_type' => $payload['reference_type'],
                'reference_id' => $payload['reference_id'],
                'is_read' => false,
                'read_at' => null,
            ]);
        }
    }
}
