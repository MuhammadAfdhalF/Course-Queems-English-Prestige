<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\CourseLevel;
use App\Models\CourseProgram;

use App\Models\User;
use App\Services\CertificatePresentationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicCertificateCourseIdentityTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected CourseProgram $program;
    protected CourseLevel $level;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->program = CourseProgram::create([
            'name' => 'General English',
            'slug' => 'general-english',
            'is_active' => true,
        ]);

        $this->level = CourseLevel::create([
            'course_program_id' => $this->program->id,
            'name' => 'Basic 1',
            'slug' => 'basic-1',
            'price' => 100000,
            'learning_mode' => 'online',
            'access_type' => 'lifetime',
            'is_active' => true,
            'certificate_score_label' => 'Final Exam Score',
        ]);
    }

    public function test_course_level_certificate_score_label_is_nullable_and_saves_custom_label(): void
    {
        $level = CourseLevel::create([
            'course_program_id' => $this->program->id,
            'name' => 'Basic 2',
            'slug' => 'basic-2',
            'price' => 100000,
            'learning_mode' => 'online',
            'access_type' => 'lifetime',
            'is_active' => true,
            'certificate_score_label' => null,
        ]);

        $this::assertNull($level->certificate_score_label);
    }

    public function test_certificate_score_label_normalizes_empty_string_to_null_and_strips_trailing_colon(): void
    {
        $this::assertNull(CertificatePresentationService::normalizeScoreLabel('   '));
        $this::assertEquals('TOEFL Prediction Score', CertificatePresentationService::normalizeScoreLabel('TOEFL Prediction Score:  '));
        $this::assertEquals('Final Test Score', CertificatePresentationService::normalizeScoreLabel('  Final Test Score  '));
        $this::assertEquals('Score', CertificatePresentationService::normalizeScoreLabel('<b>Score</b>:'));
    }

    public function test_certificate_score_label_longer_than_100_chars_is_rejected(): void
    {
        $response = $this->actingAs($this->admin)->post(
            route('admin.course-management.programs.levels.store', $this->program),
            [
                'name' => 'Level Overflow',
                'price' => '100000',
                'learning_mode' => 'online',
                'access_type' => 'lifetime',
                'thumbnail_type' => 'image',
                'certificate_score_label' => str_repeat('A', 101),
            ]
        );

        $response->assertSessionHasErrors(['certificate_score_label']);
    }

    public function test_builder_and_legacy_form_store_and_update_certificate_score_label(): void
    {
        // Builder store
        $response = $this->actingAs($this->admin)->postJson(
            route('admin.course-management.programs.levels.store', $this->program),
            [
                'name' => 'Intermediate 1',
                'price' => '150.000',
                'learning_mode' => 'online',
                'access_type' => 'lifetime',
                'thumbnail_type' => 'image',
                'certificate_score_label' => 'Intermediate Assessment Score:',
            ]
        );

        $response->assertOk();
        $newLevel = CourseLevel::where('name', 'Intermediate 1')->first();
        $this::assertNotNull($newLevel);
        $this::assertEquals('Intermediate Assessment Score', $newLevel->certificate_score_label);

        // Builder update
        $updateResponse = $this->actingAs($this->admin)->putJson(
            route('admin.course-management.levels.update', $newLevel),
            [
                'name' => 'Intermediate 1 Updated',
                'price' => '150.000',
                'learning_mode' => 'online',
                'access_type' => 'lifetime',
                'thumbnail_type' => 'image',
                'certificate_score_label' => 'Updated Score Heading',
            ]
        );

        $updateResponse->assertOk();
        $this::assertEquals('Updated Score Heading', $newLevel->fresh()->certificate_score_label);
    }

    public function test_course_display_name_rules(): void
    {
        // Program & Level different -> "Program — Level"
        $this::assertEquals(
            'General English — Basic 1',
            CertificatePresentationService::courseDisplayName($this->program, $this->level)
        );

        // Program & Level same -> single name
        $toeflProgram = CourseProgram::create(['name' => 'TOEFL Prediction', 'slug' => 'toefl-p', 'is_active' => true]);
        $toeflLevel = CourseLevel::create(['course_program_id' => $toeflProgram->id, 'name' => 'TOEFL Prediction', 'slug' => 'toefl-l', 'price' => 100000, 'learning_mode' => 'online', 'access_type' => 'lifetime', 'is_active' => true]);
        $this::assertEquals('TOEFL Prediction', CertificatePresentationService::courseDisplayName($toeflProgram, $toeflLevel));

        // Level empty -> Program name
        $this::assertEquals('General English', CertificatePresentationService::courseDisplayName($this->program, null));

        // Program empty -> Level name
        $this::assertEquals('Basic 1', CertificatePresentationService::courseDisplayName(null, $this->level));

        // Both empty -> "Course Completion"
        $this::assertEquals('Course Completion', CertificatePresentationService::courseDisplayName(null, null));
    }

    public function test_score_label_fallback_and_formatting(): void
    {
        // Custom label
        $this::assertEquals('Final Exam Score', CertificatePresentationService::scoreLabel($this->level));

        // Empty label -> Fallback "Final Score"
        $this->level->update(['certificate_score_label' => null]);
        $this::assertEquals('Final Score', CertificatePresentationService::scoreLabel($this->level));
    }

    public function test_certificate_view_renders_dynamic_course_name_and_custom_score_label(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_active' => true]);

        $enrollment = \App\Models\StudentCourseEnrollment::create([
            'student_id' => $student->id,
            'course_level_id' => $this->level->id,
            'status' => 'completed',
            'enrolled_at' => now(),
        ]);

        $finalExam = \App\Models\FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Final Exam',
            'total_score' => 100,
            'passing_score' => 70,
            'result_mode' => 'pass_fail',
            'is_active' => true,
        ]);

        $attempt = \App\Models\FinalExamAttempt::create([
            'student_id' => $student->id,
            'final_exam_id' => $finalExam->id,
            'attempt_number' => 1,
            'raw_score' => 87.50,
            'max_score' => 100,
            'percentage_score' => 87.50,
            'result_mode' => 'pass_fail',
            'status' => 'passed',
            'is_passed' => true,
            'submitted_at' => now(),
        ]);

        $template = \App\Models\CertificateTemplate::create([
            'course_program_id' => $this->program->id,
            'name' => 'Default Template',
            'is_active' => true,
        ]);

        $certificate = Certificate::create([
            'student_id' => $student->id,
            'course_level_id' => $this->level->id,
            'enrollment_id' => $enrollment->id,
            'final_exam_attempt_id' => $attempt->id,
            'certificate_template_id' => $template->id,
            'certificate_number' => 'CERT-2026-001',
            'verification_token' => 'TOKEN-123456',
            'issued_at' => now(),
            'status' => 'issued',
            'section_scores' => [
                ['title' => 'Reading', 'score' => 85],
                ['title' => 'Listening', 'score' => 90],
            ],
            'final_score' => 87.50,
        ]);

        $response = $this->actingAs($this->admin)->get(
            route('admin.course-management.certificates.show', $certificate)
        );

        $response->assertOk();
        $response->assertSee('General English — Basic 1');
        $response->assertSee('Final Exam Score:');
        $response->assertDontSee('TOEFL Prediction Score:');
    }
}
