<?php

namespace Tests\Feature;

use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\Module;
use App\Models\ModulePractice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseBuilderFormHardeningTest extends TestCase
{
    use RefreshDatabase;

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
            'name' => 'General English Prestige',
            'slug' => 'general-english-prestige',
            'is_active' => true,
        ]);

        $this->level = CourseLevel::create([
            'course_program_id' => $this->program->id,
            'name' => 'Basic Level 1',
            'slug' => 'basic-level-1',
            'price' => 100000,
            'learning_mode' => 'online',
            'access_type' => 'lifetime',
            'is_active' => true,
        ]);

        $this->module = Module::create([
            'course_level_id' => $this->level->id,
            'title' => 'Module 01 - Grammar Basics',
            'slug' => 'module-01-grammar-basics',
            'is_active' => true,
        ]);
    }

    public function test_course_level_price_normalization_accepts_formatted_indonesian_rupiah()
    {
        $response = $this->actingAs($this->admin)->post(
            route('admin.course-management.programs.levels.store', $this->program),
            [
                'name' => 'Intermediate Level 2',
                'slug' => 'intermediate-level-2',
                'thumbnail_type' => 'image',
                'price' => '150.000',
                'learning_mode' => 'online',
                'access_type' => 'lifetime',
                'is_active' => true,
            ]
        );

        $response->assertStatus(302);
        $this->assertDatabaseHas('course_levels', [
            'slug' => 'intermediate-level-2',
            'price' => 150000,
        ]);
    }

    public function test_builder_practice_store_payload_with_pass_fail_succeeds_without_422()
    {
        $response = $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.builder.practices.store', [
                'courseProgram' => $this->program->id,
                'module' => $this->module->id,
            ]),
            [
                'title' => 'Module 01 Practice Quiz',
                'description' => 'Test instructions',
                'result_mode' => 'pass_fail',
                'total_score' => 40,
                'passing_score' => 20,
                'grading_method' => 'auto',
                'attempt_mode' => 'unlimited',
                'is_required' => 1,
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('module_practices', [
            'module_id' => $this->module->id,
            'title' => 'Module 01 Practice Quiz',
            'result_mode' => 'pass_fail',
            'total_score' => 40,
            'passing_score' => 20,
            'is_active' => false, // Initial practice has 0 questions -> inactive
        ]);
    }

    public function test_builder_practice_store_with_score_only_sets_null_passing_score()
    {
        $response = $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.builder.practices.store', [
                'courseProgram' => $this->program->id,
                'module' => $this->module->id,
            ]),
            [
                'title' => 'Module 01 Diagnostic Test',
                'description' => 'Diagnostic quiz',
                'result_mode' => 'score_only',
                'total_score' => 50,
                'grading_method' => 'auto',
                'attempt_mode' => 'unlimited',
            ]
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('module_practices', [
            'module_id' => $this->module->id,
            'result_mode' => 'score_only',
            'total_score' => 50,
            'passing_score' => null,
        ]);
    }

    public function test_continuous_readiness_auto_activates_practice_when_question_scores_match_total()
    {
        // 1. Create Practice with total score 30
        $practice = ModulePractice::create([
            'module_id' => $this->module->id,
            'title' => 'Module Practice Test',
            'result_mode' => 'pass_fail',
            'total_score' => 30,
            'passing_score' => 15,
            'grading_method' => 'auto',
            'max_attempts' => null,
            'is_required' => true,
            'is_active' => false,
        ]);

        // 2. Add question 1 with score 15 -> Allocated 15/30 -> Remains inactive
        $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.builder.questions.store', [
                'courseProgram' => $this->program->id,
                'modulePractice' => $practice->id,
            ]),
            [
                'question_type' => 'short_answer',
                'question' => 'What is your name?',
                'score' => 15,
                'is_active' => 1,
            ]
        );

        $practice->refresh();
        $this->assertFalse((bool) $practice->is_active);

        // 3. Add question 2 with score 15 -> Allocated 30/30 (EXACT) -> Auto-activates!
        $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.builder.questions.store', [
                'courseProgram' => $this->program->id,
                'modulePractice' => $practice->id,
            ]),
            [
                'question_type' => 'short_answer',
                'question' => 'Where do you live?',
                'score' => 15,
                'is_active' => 1,
            ]
        );

        $practice->refresh();
        $this->assertTrue((bool) $practice->is_active);
    }

    public function test_invalid_price_formats_are_rejected()
    {
        $invalidPrices = [
            '-100000',
            'Rp -100.000',
            'abc100000',
            '100000abc',
            '100,50',
        ];

        foreach ($invalidPrices as $invalidPrice) {
            $response = $this->actingAs($this->admin)->post(
                route('admin.course-management.programs.levels.store', $this->program),
                [
                    'name' => 'Invalid Price Level',
                    'slug' => 'invalid-price-level-' . uniqid(),
                    'thumbnail_type' => 'image',
                    'price' => $invalidPrice,
                    'learning_mode' => 'online',
                    'access_type' => 'lifetime',
                    'is_active' => true,
                ]
            );

            $response->assertSessionHasErrors('price');
        }
    }

    public function test_builder_final_exam_section_store_payload_succeeds_without_422()
    {
        $response = $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.builder.final-exams.store', [
                'courseProgram' => $this->program->id,
                'courseLevel' => $this->level->id,
            ]),
            [
                'title' => 'Final Exam Listening',
                'description' => 'Listening section',
                'result_mode' => 'pass_fail',
                'total_score' => 50,
                'passing_score' => 25,
                'grading_method' => 'auto',
                'attempt_mode' => 'unlimited',
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('final_exams', [
            'course_level_id' => $this->level->id,
            'title' => 'Final Exam Listening',
            'result_mode' => 'pass_fail',
            'total_score' => 50,
            'passing_score' => 25,
            'is_active' => false,
        ]);
    }

    public function test_continuous_readiness_auto_activates_final_exam_section()
    {
        $finalExam = \App\Models\FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Final Exam Reading',
            'result_mode' => 'pass_fail',
            'total_score' => 50,
            'passing_score' => 25,
            'grading_method' => 'auto',
            'max_attempts' => null,
            'is_active' => false,
        ]);

        $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.builder.final-exam-questions.store', [
                'courseProgram' => $this->program->id,
                'finalExam' => $finalExam->id,
            ]),
            [
                'question_type' => 'multiple_choice',
                'question' => 'Select correct answer',
                'options' => ['A' => 'Opt A', 'B' => 'Opt B', 'C' => 'Opt C', 'D' => 'Opt D'],
                'correct_option' => 'A',
                'score' => 50,
                'is_active' => 1,
            ]
        );

        $finalExam->refresh();
        $this->assertTrue((bool) $finalExam->is_active);
    }
}
