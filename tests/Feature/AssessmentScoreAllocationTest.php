<?php

namespace Tests\Feature;

use App\Enums\AssessmentResultMode;
use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\FinalExam;
use App\Models\FinalExamAttempt;
use App\Models\FinalExamQuestion;
use App\Models\FreeTest;
use App\Models\FreeTestQuestion;
use App\Models\FreeTestResult;
use App\Models\Module;
use App\Models\ModulePractice;
use App\Models\ModulePracticeAttempt;
use App\Models\ModulePracticeQuestion;
use App\Models\User;
use App\Services\AssessmentConfigService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AssessmentScoreAllocationTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected CourseProgram $program;
    protected CourseLevel $level;
    protected Module $module;
    protected AssessmentConfigService $configService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $uid = uniqid();
        $this->program = CourseProgram::create([
            'name' => 'Score Test Program ' . $uid,
            'slug' => 'score-test-program-' . $uid,
            'is_active' => true,
        ]);

        $this->level = CourseLevel::create([
            'course_program_id' => $this->program->id,
            'name' => 'Level ' . $uid,
            'slug' => 'level-' . $uid,
            'level_number' => 1,
            'is_active' => true,
        ]);

        $this->module = Module::create([
            'course_level_id' => $this->level->id,
            'title' => 'Module ' . $uid,
            'slug' => 'module-' . $uid,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->configService = app(AssessmentConfigService::class);
    }

    public function test_allocation_calculation_precision()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Final Exam Allocation Test',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => false,
        ]);

        FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'multiple_choice',
            'question' => 'Q1',
            'score' => 45.50,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'multiple_choice',
            'question' => 'Q2 (Inactive)',
            'score' => 20.00,
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $readiness = $this->configService->getReadinessStatus($exam);

        $this->assertEquals(100.00, $readiness['total_score']);
        $this->assertEquals(45.50, $readiness['allocated_score']);
        $this->assertEquals(54.50, $readiness['remaining_score']);
        $this->assertEquals('incomplete', $readiness['status']);
    }

    public function test_create_question_over_allocation_rejected()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Final Exam Over Allocation Test',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => false,
        ]);

        FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'multiple_choice',
            'question' => 'Q1',
            'score' => 90.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.course-management.programs.builder.final-exam-questions.store', [
                'courseProgram' => $this->program->id,
                'finalExam' => $exam->id,
            ]), [
                'question_type' => 'multiple_choice',
                'question' => 'Q2 Over',
                'score' => 20.00,
                'is_active' => 1,
                'options' => ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'],
                'correct_option' => 'A',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['score']);
    }

    public function test_delete_active_question_auto_deactivates_assessment()
    {
        $practice = ModulePractice::create([
            'module_id' => $this->module->id,
            'title' => 'Practice Deactivation Test',
            'grading_method' => 'auto',
            'result_mode' => AssessmentResultMode::SCORE_ONLY,
            'total_score' => 50.00,
            'passing_score' => null,
            'is_active' => true,
        ]);

        $q = ModulePracticeQuestion::create([
            'module_practice_id' => $practice->id,
            'question_type' => 'multiple_choice',
            'question' => 'Q1',
            'score' => 50.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson(route('admin.course-management.programs.builder.questions.destroy', [
                'courseProgram' => $this->program->id,
                'modulePracticeQuestion' => $q->id,
            ]));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('module_practice_questions', ['id' => $q->id]);
        $this->assertDatabaseHas('module_practices', [
            'id' => $practice->id,
            'is_active' => false,
        ]);
    }

    public function test_activation_fails_if_score_incomplete()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Activation Incomplete Test',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => false,
        ]);

        FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'multiple_choice',
            'question' => 'Q1',
            'score' => 50.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->patchJson(route('admin.course-management.programs.builder.final-exams.toggle-active', [
                'courseProgram' => $this->program->id,
                'finalExam' => $exam->id,
            ]));

        $response->assertStatus(422);
        $this->assertDatabaseHas('final_exams', [
            'id' => $exam->id,
            'is_active' => false,
        ]);
    }

    public function test_activation_succeeds_when_score_exact()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Activation Exact Test',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => false,
        ]);

        FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'multiple_choice',
            'question' => 'Q1',
            'score' => 100.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->patchJson(route('admin.course-management.programs.builder.final-exams.toggle-active', [
                'courseProgram' => $this->program->id,
                'finalExam' => $exam->id,
            ]));

        $response->assertStatus(200);
        $this->assertDatabaseHas('final_exams', [
            'id' => $exam->id,
            'is_active' => true,
        ]);
    }

    public function test_permanent_question_locking_when_student_history_exists()
    {
        $student = User::factory()->create(['role' => 'student']);

        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Locked Exam Test',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        $q = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'multiple_choice',
            'question' => 'Q1',
            'score' => 100.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        FinalExamAttempt::create([
            'final_exam_id' => $exam->id,
            'student_id' => $student->id,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'status' => 'submitted',
            'attempt_number' => 1,
        ]);

        // Attempting to delete question should be rejected
        $response = $this->actingAs($this->admin)
            ->deleteJson(route('admin.course-management.programs.builder.final-exam-questions.destroy', [
                'courseProgram' => $this->program->id,
                'finalExamQuestion' => $q->id,
            ]));

        $response->assertStatus(422);

        // Attempting to store new question should be rejected
        $storeResponse = $this->actingAs($this->admin)
            ->postJson(route('admin.course-management.programs.builder.final-exam-questions.store', [
                'courseProgram' => $this->program->id,
                'finalExam' => $exam->id,
            ]), [
                'question_type' => 'multiple_choice',
                'question' => 'Q2 New',
                'score' => 10.00,
                'is_active' => 1,
                'options' => ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'],
                'correct_option' => 'A',
            ]);

        $storeResponse->assertStatus(422);
    }
}
