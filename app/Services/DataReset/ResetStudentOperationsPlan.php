<?php

namespace App\Services\DataReset;

class ResetStudentOperationsPlan extends ResetPlan
{
    public function getResetType(): string
    {
        return 'student_operations';
    }

    public function getConfirmationPhrase(): string
    {
        return 'RESET STUDENT OPERATIONS';
    }

    public function getDeletionSteps(): array
    {
        return [
            ['table' => 'notifications', 'type' => 'all'],
            ['table' => 'certificates', 'type' => 'all'],
            ['table' => 'testimonials', 'type' => 'all'],
            ['table' => 'free_test_results', 'type' => 'all'],
            ['table' => 'payments', 'type' => 'all'],
            ['table' => 'student_module_progress', 'type' => 'all'],
            ['table' => 'student_course_enrollments', 'type' => 'all'],
            ['table' => 'orders', 'type' => 'all'],
            ['table' => 'final_exam_answers', 'type' => 'all'],
            ['table' => 'final_exam_attempts', 'type' => 'all'],
            ['table' => 'module_practice_answers', 'type' => 'all'],
            ['table' => 'module_practice_attempts', 'type' => 'all'],
            ['table' => 'sessions', 'type' => 'filtered_user_id'],
            ['table' => 'password_reset_tokens', 'type' => 'filtered_email'],
            ['table' => 'student_profiles', 'type' => 'all'],
            ['table' => 'users', 'type' => 'filtered_role_student'],
        ];
    }
}
