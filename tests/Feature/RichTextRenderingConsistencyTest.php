<?php

namespace Tests\Feature;

use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\FinalExam;
use App\Models\Module;
use App\Models\ModulePractice;
use App\Models\ModulePracticeQuestion;
use App\Models\User;
use App\Support\RichText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RichTextRenderingConsistencyTest extends TestCase
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
            'short_description' => '<p>Basic 1 Short Description</p>',
            'is_active' => true,
        ]);

        $this->module = Module::create([
            'course_level_id' => $this->level->id,
            'title' => 'Module 1',
            'slug' => 'module-1',
            'short_description' => '<p>Module 1 Short Description</p>',
            'is_active' => true,
        ]);

        $this->practice = ModulePractice::create([
            'module_id' => $this->module->id,
            'title' => 'Practice Quiz 1',
            'description' => '<p>Practice Instructions: Follow all instructions carefully.</p>',
            'total_score' => 30,
            'passing_score' => 20,
            'result_mode' => 'pass_fail',
            'is_active' => false,
        ]);

        $this->finalExam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Final Exam Section 1',
            'description' => '<p>Ujian akhir untuk course Basic 1.</p>',
            'total_score' => 50,
            'passing_score' => 35,
            'result_mode' => 'pass_fail',
            'is_active' => false,
        ]);
    }

    public function test_rich_text_helper_to_plain_text_formatting(): void
    {
        $this::assertEquals('asd', RichText::toPlainText('<p>asd</p>'));
        $this::assertEquals('First line Second line', RichText::toPlainText('<p>First line</p><p>Second line</p>'));
        $this::assertEquals('Hello World', RichText::toPlainText('<p>Hello&nbsp;World</p>'));
        $this::assertEquals('', RichText::toPlainText(null));
        $this::assertEquals('Short text', RichText::toPlainText('<p>Short text</p>', 50));
    }

    public function test_practice_workspace_renders_rich_instructions_html(): void
    {
        $response = $this->actingAs($this->admin)->get(
            route('admin.course-management.programs.builder.workspace', [
                'courseProgram' => $this->program->id,
                'level' => $this->level->id,
                'module' => $this->module->id,
                'tab' => 'practice',
            ])
        );

        $response->assertOk();
        $html = $response->json('html');
        $this->assertStringContainsString('Practice Instructions', $html);
        $this->assertStringContainsString('<p>Practice Instructions: Follow all instructions carefully.</p>', $html);
        $this->assertStringNotContainsString('&lt;p&gt;Practice Instructions', $html);
    }

    public function test_final_exam_section_workspace_renders_rich_description_html(): void
    {
        $response = $this->actingAs($this->admin)->get(
            route('admin.course-management.programs.builder.workspace', [
                'courseProgram' => $this->program->id,
                'level' => $this->level->id,
                'exam' => $this->finalExam->id,
                'tab' => 'questions',
            ])
        );

        $response->assertOk();
        $html = $response->json('html');
        $this->assertStringContainsString('<p>Ujian akhir untuk course Basic 1.</p>', $html);
        $this->assertStringNotContainsString('&lt;p&gt;Ujian akhir', $html);
    }

    public function test_final_exam_folder_card_renders_plain_text_summary(): void
    {
        $response = $this->actingAs($this->admin)->get(
            route('admin.course-management.programs.builder.workspace', [
                'courseProgram' => $this->program->id,
                'level' => $this->level->id,
                'tab' => 'final-exam',
            ])
        );

        $response->assertOk();
        $html = $response->json('html');
        $this->assertStringContainsString('Ujian akhir untuk course Basic 1.', $html);
        $this->assertStringNotContainsString('<p>Ujian akhir untuk course Basic 1.</p>', $html);
        $this->assertStringNotContainsString('&lt;p&gt;Ujian akhir', $html);
    }
}
