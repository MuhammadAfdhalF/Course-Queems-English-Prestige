<?php

namespace App\Services\DataReset;

class ResetPreProductionPlan extends ResetPlan
{
    public function getResetType(): string
    {
        return 'pre_production';
    }

    public function getConfirmationPhrase(): string
    {
        return 'RESET PRE PRODUCTION DATA';
    }

    public function getDeletionSteps(): array
    {
        return [
            ['table' => 'notifications', 'type' => 'all'],
            ['table' => 'certificates', 'type' => 'all'],
            ['table' => 'certificate_templates', 'type' => 'all'],
            ['table' => 'testimonials', 'type' => 'all'],
            ['table' => 'free_test_results', 'type' => 'all'],
            ['table' => 'free_test_questions', 'type' => 'all'],
            ['table' => 'free_tests', 'type' => 'all'],
            ['table' => 'free_test_categories', 'type' => 'all'],
            ['table' => 'payments', 'type' => 'all'],
            ['table' => 'student_module_progress', 'type' => 'all'],
            ['table' => 'student_course_enrollments', 'type' => 'all'],
            ['table' => 'orders', 'type' => 'all'],
            ['table' => 'final_exam_answers', 'type' => 'all'],
            ['table' => 'final_exam_attempts', 'type' => 'all'],
            ['table' => 'final_exam_question_options', 'type' => 'all'],
            ['table' => 'final_exam_questions', 'type' => 'all'],
            ['table' => 'final_exams', 'type' => 'all'],
            ['table' => 'module_practice_answers', 'type' => 'all'],
            ['table' => 'module_practice_attempts', 'type' => 'all'],
            ['table' => 'module_practice_question_options', 'type' => 'all'],
            ['table' => 'module_practice_questions', 'type' => 'all'],
            ['table' => 'module_practices', 'type' => 'all'],
            ['table' => 'module_materials', 'type' => 'all'],
            ['table' => 'modules', 'type' => 'all'],
            ['table' => 'course_levels', 'type' => 'all'],
            ['table' => 'course_programs', 'type' => 'all'],
            ['table' => 'sessions', 'type' => 'filtered_user_id'],
            ['table' => 'password_reset_tokens', 'type' => 'filtered_email'],
            ['table' => 'student_profiles', 'type' => 'all'],
            ['table' => 'users', 'type' => 'filtered_role_student'],
        ];
    }
}
