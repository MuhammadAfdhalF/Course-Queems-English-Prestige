<?php

namespace Tests\Feature;

use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\FinalExam;
use App\Models\FinalExamQuestion;
use App\Models\Module;
use App\Models\ModulePractice;
use App\Models\ModulePracticeQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionScoreAllocationGuidanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected CourseProgram $program;
    protected CourseLevel $level;
    protected Module $module;
    protected ModulePractice $practice;
    protected FinalExam $finalExam;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->program = CourseProgram::create([
            'name' => 'General English Prestige',
            'slug' => 'general-english-prestige',
            'is_active' => true,
        ]);

        $this->level = CourseLevel::create([
            'course_program_id' => $this->program->id,
            'name' => 'Basic Level 1',
            'slug' => 'basic-level-1',
            'is_active' => true,
        ]);

        $this->module = Module::create([
            'course_level_id' => $this->level->id,
            'title' => 'Module 1',
            'slug' => 'module-1',
            'is_active' => true,
        ]);

        $this->practice = ModulePractice::create([
            'module_id' => $this->module->id,
            'title' => 'Practice Quiz 1',
            'total_score' => 30,
            'passing_score' => 20,
            'result_mode' => 'pass_fail',
            'is_active' => false,
        ]);

        $this->finalExam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Final Exam Section 1',
            'total_score' => 50,
            'passing_score' => 35,
            'result_mode' => 'pass_fail',
            'is_active' => false,
        ]);
    }

    public function test_module_practice_question_builder_edit_returns_allocation_context(): void
    {
        $question = ModulePracticeQuestion::create([
            'module_practice_id' => $this->practice->id,
            'question_type' => 'multiple_choice',
            'question' => 'Sample Question 1',
            'score' => 10,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->getJson(
            route('admin.course-management.programs.builder.questions.edit', [
                'courseProgram' => $this->program->id,
                'modulePracticeQuestion' => $question->id,
            ])
        );

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.allocation.total_score', 30)
            ->assertJsonPath('data.allocation.allocated_score', 10)
            ->assertJsonPath('data.allocation.remaining_score', 20);
    }

    public function test_final_exam_question_builder_edit_returns_allocation_context(): void
    {
        $question = FinalExamQuestion::create([
            'final_exam_id' => $this->finalExam->id,
            'question_type' => 'multiple_choice',
            'question' => 'Exam Question 1',
            'score' => 15,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->getJson(
            route('admin.course-management.programs.builder.final-exam-questions.edit', [
                'courseProgram' => $this->program->id,
                'finalExamQuestion' => $question->id,
            ])
        );

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.allocation.total_score', 50)
            ->assertJsonPath('data.allocation.allocated_score', 15)
            ->assertJsonPath('data.allocation.remaining_score', 35);
    }

    public function test_cannot_store_question_that_exceeds_remaining_allocation(): void
    {
        ModulePracticeQuestion::create([
            'module_practice_id' => $this->practice->id,
            'question_type' => 'multiple_choice',
            'question' => 'Question 1',
            'score' => 25,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.builder.questions.store', [
                'courseProgram' => $this->program->id,
                'modulePractice' => $this->practice->id,
            ]),
            [
                'question_type' => 'multiple_choice',
                'question' => 'Question 2 Over Allocation',
                'score' => 10, // 25 + 10 = 35 > 30 Total
                'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C', 'D' => 'Option D'],
                'correct_option' => 'A',
                'is_active' => '1',
            ]
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['score']);
    }
}
