<?php

namespace Tests\Feature;

use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\Module;
use App\Models\ModuleMaterial;
use App\Models\StudentCourseEnrollment;
use App\Models\StudentModuleProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCourseModulePreviewTest extends TestCase
{
    use RefreshDatabase;

    private CourseProgram $program;
    private CourseLevel $courseLevel;
    private Module $previewModule;
    private Module $nonPreviewModule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->program = CourseProgram::create([
            'name' => 'General English',
            'slug' => 'general-english',
            'is_active' => true,
        ]);

        $this->courseLevel = CourseLevel::create([
            'course_program_id' => $this->program->id,
            'name' => 'Beginner Level 1',
            'slug' => 'beginner-level-1',
            'learning_mode' => 'online',
            'price' => 1500000,
            'is_active' => true,
        ]);

        $this->previewModule = Module::create([
            'course_level_id' => $this->courseLevel->id,
            'title' => 'Introduction & Greetings',
            'slug' => 'introduction-greetings',
            'short_description' => 'Learn basic greetings and introductions.',
            'sort_order' => 1,
            'is_preview' => true,
            'is_active' => true,
        ]);

        $this->nonPreviewModule = Module::create([
            'course_level_id' => $this->courseLevel->id,
            'title' => 'Advanced Grammar',
            'slug' => 'advanced-grammar',
            'short_description' => 'Deep dive into complex sentence structures.',
            'sort_order' => 2,
            'is_preview' => false,
            'is_active' => true,
        ]);
    }

    public function test_guest_can_view_active_preview_module(): void
    {
        ModuleMaterial::create([
            'module_id' => $this->previewModule->id,
            'title' => 'Welcome Text',
            'material_type' => 'text',
            'content' => '<p>Hello and welcome to the course preview.</p>',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get(route('courses.preview-module', [
            'courseLevel' => $this->courseLevel->slug,
            'module' => $this->previewModule->slug,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Public Preview');
        $response->assertSee('Introduction &amp; Greetings', false);
        $response->assertSee('Hello and welcome to the course preview.', false);
    }

    public function test_authenticated_student_without_enrollment_can_view_preview(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->get(route('courses.preview-module', [
            'courseLevel' => $this->courseLevel->slug,
            'module' => $this->previewModule->slug,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Public Preview');
        $response->assertSee('Enroll Course Now');
    }

    public function test_enrolled_student_can_open_preview_route_without_creating_progress(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $enrollment = StudentCourseEnrollment::create([
            'student_id' => $student->id,
            'course_level_id' => $this->courseLevel->id,
            'status' => 'active',
            'progress_percentage' => 0,
        ]);

        $initialProgressCount = StudentModuleProgress::count();

        $response = $this->actingAs($student)->get(route('courses.preview-module', [
            'courseLevel' => $this->courseLevel->slug,
            'module' => $this->previewModule->slug,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Continue Learning');
        $this::assertEquals($initialProgressCount, StudentModuleProgress::count());
        $this::assertEquals(0, $enrollment->fresh()->progress_percentage);
    }

    public function test_course_detail_shows_read_preview_for_guest(): void
    {
        $response = $this->get(route('courses.show', $this->courseLevel->slug));

        $response->assertStatus(200);
        $response->assertSee('Read Preview');
    }

    public function test_course_detail_shows_continue_learning_for_active_enrolled_student(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        StudentCourseEnrollment::create([
            'student_id' => $student->id,
            'course_level_id' => $this->courseLevel->id,
            'status' => 'active',
            'progress_percentage' => 10,
        ]);

        $response = $this->actingAs($student)->get(route('courses.show', $this->courseLevel->slug));

        $response->assertStatus(200);
        $response->assertSee('Continue Learning');
    }

    public function test_non_preview_module_returns_404(): void
    {
        $response = $this->get(route('courses.preview-module', [
            'courseLevel' => $this->courseLevel->slug,
            'module' => $this->nonPreviewModule->slug,
        ]));

        $response->assertStatus(404);
    }

    public function test_module_belonging_to_another_course_level_returns_404(): void
    {
        $otherLevel = CourseLevel::create([
            'course_program_id' => $this->program->id,
            'name' => 'Intermediate Level 2',
            'slug' => 'intermediate-level-2',
            'price' => 2000000,
            'is_active' => true,
        ]);

        $response = $this->get(route('courses.preview-module', [
            'courseLevel' => $otherLevel->slug,
            'module' => $this->previewModule->slug,
        ]));

        $response->assertStatus(404);
    }

    public function test_inactive_module_returns_404(): void
    {
        $inactiveModule = Module::create([
            'course_level_id' => $this->courseLevel->id,
            'title' => 'Inactive Preview',
            'slug' => 'inactive-preview',
            'is_preview' => true,
            'is_active' => false,
        ]);

        $response = $this->get(route('courses.preview-module', [
            'courseLevel' => $this->courseLevel->slug,
            'module' => $inactiveModule->slug,
        ]));

        $response->assertStatus(404);
    }

    public function test_inactive_course_level_returns_404(): void
    {
        $this->courseLevel->update(['is_active' => false]);

        $response = $this->get(route('courses.preview-module', [
            'courseLevel' => $this->courseLevel->slug,
            'module' => $this->previewModule->slug,
        ]));

        $response->assertStatus(404);
    }

    public function test_inactive_course_program_returns_404(): void
    {
        $this->program->update(['is_active' => false]);

        $response = $this->get(route('courses.preview-module', [
            'courseLevel' => $this->courseLevel->slug,
            'module' => $this->previewModule->slug,
        ]));

        $response->assertStatus(404);
    }

    public function test_text_and_image_materials_are_rendered_but_media_and_file_downloads_are_excluded(): void
    {
        ModuleMaterial::create([
            'module_id' => $this->previewModule->id,
            'title' => 'Text Material',
            'material_type' => 'text',
            'content' => '<p>Readable text material.</p>',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        ModuleMaterial::create([
            'module_id' => $this->previewModule->id,
            'title' => 'Image Material',
            'material_type' => 'image',
            'file_path' => 'materials/sample.jpg',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        ModuleMaterial::create([
            'module_id' => $this->previewModule->id,
            'title' => 'Secret Video Material',
            'material_type' => 'video',
            'file_path' => 'materials/secret-video.mp4',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        ModuleMaterial::create([
            'module_id' => $this->previewModule->id,
            'title' => 'Secret PDF Material',
            'material_type' => 'pdf',
            'file_path' => 'materials/secret-file.pdf',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $response = $this->get(route('courses.preview-module', [
            'courseLevel' => $this->courseLevel->slug,
            'module' => $this->previewModule->slug,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Readable text material.', false);
        $response->assertSee('materials/sample.jpg');
        $response->assertDontSee('materials/secret-video.mp4');
        $response->assertDontSee('materials/secret-file.pdf');
        $response->assertSee('Additional media (video, audio, or downloadable files) is available after enrolling in this course.');
    }

    public function test_empty_readable_content_displays_prepared_empty_state(): void
    {
        $response = $this->get(route('courses.preview-module', [
            'courseLevel' => $this->courseLevel->slug,
            'module' => $this->previewModule->slug,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Preview content for this module is being prepared.');
    }

    public function test_preview_page_does_not_render_practice_final_exam_or_mark_complete(): void
    {
        $response = $this->get(route('courses.preview-module', [
            'courseLevel' => $this->courseLevel->slug,
            'module' => $this->previewModule->slug,
        ]));

        $response->assertStatus(200);
        $response->assertDontSee('Practice Available');
        $response->assertDontSee('Mark as Complete');
        $response->assertDontSee('Final Exam');
    }
}
