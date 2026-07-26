<?php

namespace Tests\Feature;

use App\Enums\AssessmentResultMode;
use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\FinalExam;
use App\Models\FinalExamAttempt;
use App\Models\FreeTest;
use App\Models\FreeTestResult;
use App\Models\Module;
use App\Models\ModulePractice;
use App\Models\ModulePracticeAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminAssessmentConfigurationTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected CourseProgram $program;
    protected CourseLevel $level;
    protected Module $module;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->program = CourseProgram::create([
            'name' => 'Test Program ' . uniqid(),
            'slug' => 'test-program-' . uniqid(),
            'is_active' => true,
        ]);

        $uid = uniqid();
        $this->level = CourseLevel::create([
            'course_program_id' => $this->program->id,
            'name' => 'Basic 1 ' . $uid,
            'slug' => 'basic-1-' . $uid,
            'level_number' => 1,
            'is_active' => true,
        ]);

        $this->module = Module::create([
            'course_level_id' => $this->level->id,
            'title' => 'Module 1',
            'slug' => 'module-1-' . $uid,
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_final_exam_pass_fail(): void
    {
        $response = $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.builder.final-exams.store', [
                'courseProgram' => $this->program->id,
                'courseLevel' => $this->level->id,
            ]),
            [
                'title' => 'Listening Section',
                'description' => 'Test instructions',
                'result_mode' => 'pass_fail',
                'total_score' => 100,
                'passing_score' => 75,
                'grading_method' => 'auto',
                'attempt_mode' => 'unlimited',
                'is_active' => true,
            ]
        );

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('final_exams', [
            'course_level_id' => $this->level->id,
            'title' => 'Listening Section',
            'result_mode' => 'pass_fail',
            'total_score' => 100.00,
            'passing_score' => 75.00,
            'max_attempts' => null,
            'is_active' => false, // Always forced inactive on create until questions added
        ]);
    }

    public function test_admin_creating_score_only_final_exam_forces_null_passing_score(): void
    {
        $response = $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.builder.final-exams.store', [
                'courseProgram' => $this->program->id,
                'courseLevel' => $this->level->id,
            ]),
            [
                'title' => 'Structure Section',
                'result_mode' => 'score_only',
                'total_score' => 50,
                'passing_score' => 40, // Tampered input
                'grading_method' => 'auto',
                'attempt_mode' => 'one',
                'is_active' => false,
            ]
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('final_exams', [
            'title' => 'Structure Section',
            'result_mode' => 'score_only',
            'total_score' => 50.00,
            'passing_score' => null, // Forced NULL
            'max_attempts' => 1,
        ]);
    }

    public function test_final_exam_passing_score_exceeding_total_score_is_rejected(): void
    {
        $response = $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.builder.final-exams.store', [
                'courseProgram' => $this->program->id,
                'courseLevel' => $this->level->id,
            ]),
            [
                'title' => 'Invalid Section',
                'result_mode' => 'pass_fail',
                'total_score' => 50,
                'passing_score' => 60, // Exceeds total_score
                'grading_method' => 'auto',
                'attempt_mode' => 'unlimited',
            ]
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['passing_score']);
    }

    public function test_final_exam_multiple_attempts_requires_minimum_two(): void
    {
        $response = $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.builder.final-exams.store', [
                'courseProgram' => $this->program->id,
                'courseLevel' => $this->level->id,
            ]),
            [
                'title' => 'Invalid Attempt Section',
                'result_mode' => 'pass_fail',
                'total_score' => 100,
                'passing_score' => 75,
                'grading_method' => 'auto',
                'attempt_mode' => 'multiple',
                'max_attempts' => 1, // Invalid for multiple
            ]
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['max_attempts']);
    }

    public function test_scoring_config_change_deactivates_assessment(): void
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Initial Exam',
            'total_score' => 100.00,
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'passing_score' => 75.00,
            'grading_method' => 'auto',
            'max_attempts' => null,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->putJson(
            route('admin.course-management.programs.builder.final-exams.update', [
                'courseProgram' => $this->program->id,
                'finalExam' => $exam->id,
            ]),
            [
                'title' => 'Updated Exam',
                'result_mode' => 'pass_fail',
                'total_score' => 120.00, // Total score changed
                'passing_score' => 90.00,
                'grading_method' => 'auto',
                'attempt_mode' => 'unlimited',
                'is_active' => true, // Requested active
            ]
        );

        $response->assertStatus(200);

        $exam->refresh();
        $this->assertEquals(120.00, (float) $exam->total_score);
        $this->assertFalse($exam->is_active, 'Assessment should be deactivated when scoring config changes');
    }

    public function test_cannot_change_scoring_config_when_attempts_exist(): void
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Exam With Attempts',
            'total_score' => 100.00,
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'passing_score' => 75.00,
            'grading_method' => 'auto',
            'max_attempts' => null,
            'is_active' => true,
        ]);

        $student = User::factory()->create(['role' => 'student']);
        FinalExamAttempt::create([
            'student_id' => $student->id,
            'final_exam_id' => $exam->id,
            'attempt_number' => 1,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($this->admin)->putJson(
            route('admin.course-management.programs.builder.final-exams.update', [
                'courseProgram' => $this->program->id,
                'finalExam' => $exam->id,
            ]),
            [
                'title' => 'Attempted Edit Exam',
                'result_mode' => 'score_only', // Changing mode when attempts exist
                'total_score' => 100.00,
                'grading_method' => 'auto',
                'attempt_mode' => 'unlimited',
                'is_active' => true,
            ]
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['total_score']);
    }

    public function test_admin_can_create_and_update_module_practice(): void
    {
        $response = $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.builder.practices.store', [
                'courseProgram' => $this->program->id,
                'module' => $this->module->id,
            ]),
            [
                'title' => 'Module 1 Quiz',
                'description' => 'Quiz instructions',
                'result_mode' => 'pass_fail',
                'total_score' => 50,
                'passing_score' => 40,
                'grading_method' => 'auto',
                'attempt_mode' => 'multiple',
                'max_attempts' => 3,
                'is_required' => true,
                'is_active' => true,
            ]
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('module_practices', [
            'module_id' => $this->module->id,
            'title' => 'Module 1 Quiz',
            'result_mode' => 'pass_fail',
            'total_score' => 50.00,
            'passing_score' => 40.00,
            'max_attempts' => 3,
        ]);
    }

    public function test_admin_can_create_free_test_score_only(): void
    {
        $response = $this->actingAs($this->admin)->post(
            route('admin.cms.free-tests.store'),
            [
                'title' => 'Public TOEFL Test',
                'description' => 'Free placement test',
                'duration_minutes' => 60,
                'result_mode' => 'score_only',
                'total_score' => 120,
                'is_active' => true,
            ]
        );

        $response->assertRedirect(route('admin.cms.free-tests.index'));

        $this->assertDatabaseHas('free_tests', [
            'title' => 'Public TOEFL Test',
            'result_mode' => 'score_only',
            'total_score' => 120.00,
            'passing_score' => null,
        ]);
    }
}
