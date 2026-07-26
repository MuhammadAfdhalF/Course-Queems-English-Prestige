<?php

namespace Tests\Feature;

use App\Enums\AssessmentResultMode;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\FinalExam;
use App\Models\FinalExamAttempt;
use App\Models\FinalExamQuestion;
use App\Models\Module;
use App\Models\ModulePractice;
use App\Models\ModulePracticeAnswer;
use App\Models\ModulePracticeAttempt;
use App\Models\ModulePracticeQuestion;
use App\Models\StudentCourseEnrollment;
use App\Models\StudentModuleProgress;
use App\Models\User;
use App\Services\CertificateService;
use App\Services\StudentProgressService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CertificateAndProgressIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected User $student;
    protected CourseProgram $program;
    protected CourseLevel $level;
    protected Module $module1;
    protected Module $module2;
    protected StudentCourseEnrollment $enrollment;
    protected CertificateService $certificateService;
    protected StudentProgressService $progressService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->certificateService = app(CertificateService::class);
        $this->progressService = app(StudentProgressService::class);

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $uid = uniqid();
        $this->program = CourseProgram::create([
            'name' => 'Certificate Program ' . $uid,
            'slug' => 'cert-program-' . $uid,
            'is_active' => true,
        ]);

        CertificateTemplate::create([
            'course_program_id' => $this->program->id,
            'name' => 'Template ' . $uid,
            'is_default' => true,
            'is_active' => true,
        ]);

        $this->level = CourseLevel::create([
            'course_program_id' => $this->program->id,
            'name' => 'Level ' . $uid,
            'slug' => 'level-' . $uid,
            'level_number' => 1,
            'is_active' => true,
        ]);

        $this->module1 = Module::create([
            'course_level_id' => $this->level->id,
            'title' => 'Module 1 ' . $uid,
            'slug' => 'mod-1-' . $uid,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->module2 = Module::create([
            'course_level_id' => $this->level->id,
            'title' => 'Module 2 ' . $uid,
            'slug' => 'mod-2-' . $uid,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $this->enrollment = StudentCourseEnrollment::create([
            'student_id' => $this->student->id,
            'course_level_id' => $this->level->id,
            'status' => 'active',
            'progress_percentage' => 0.00,
        ]);
    }

    public function test_certificate_issued_when_all_active_sections_qualifying_pass_fail()
    {
        $exam1 = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Section 1 Listening',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        FinalExamQuestion::create([
            'final_exam_id' => $exam1->id,
            'question_type' => 'multiple_choice',
            'question' => 'Q1',
            'score' => 100.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $exam2 = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Section 2 Reading',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 60.00,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        FinalExamQuestion::create([
            'final_exam_id' => $exam2->id,
            'question_type' => 'multiple_choice',
            'question' => 'Q2',
            'score' => 100.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Attempt 1: Exam 1 passed (80.00)
        FinalExamAttempt::create([
            'final_exam_id' => $exam1->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'passing_score' => 70.00,
            'raw_score' => 80.00,
            'percentage_score' => 80.00,
            'is_passed' => true,
            'status' => 'passed',
            'submitted_at' => now(),
            'graded_at' => now(),
        ]);

        // Attempt 2: Exam 2 passed (90.00)
        FinalExamAttempt::create([
            'final_exam_id' => $exam2->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'passing_score' => 60.00,
            'raw_score' => 90.00,
            'percentage_score' => 90.00,
            'is_passed' => true,
            'status' => 'passed',
            'submitted_at' => now(),
            'graded_at' => now(),
        ]);

        $cert = $this->certificateService->evaluateAndCreateForEnrollment($this->enrollment);

        $this->assertNotNull($cert);
        $this->assertEquals(85.00, (float) $cert->final_score); // Average (80 + 90) / 2 = 85.00
        $this->assertEquals('completed', $this->enrollment->fresh()->status);
        $this->assertEquals(100.00, (float) $this->enrollment->fresh()->progress_percentage);

        // Verify section_scores JSON payload
        $snapshots = $cert->section_scores;
        $this->assertCount(2, $snapshots);
        $this->assertEquals(80.00, (float) $snapshots[0]['raw_score']);
        $this->assertEquals('pass_fail', $snapshots[0]['result_mode']);
        $this->assertTrue((bool) $snapshots[0]['is_passed']);
    }

    public function test_certificate_not_issued_if_any_active_section_failed_or_waiting_review()
    {
        $exam1 = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Exam 1',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        FinalExamQuestion::create([
            'final_exam_id' => $exam1->id,
            'question_type' => 'multiple_choice',
            'question' => 'Q1',
            'score' => 100.00,
            'is_active' => true,
        ]);

        $exam2 = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Exam 2',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        FinalExamQuestion::create([
            'final_exam_id' => $exam2->id,
            'question_type' => 'essay',
            'question' => 'Q2',
            'score' => 100.00,
            'is_active' => true,
        ]);

        // Exam 1 passed
        FinalExamAttempt::create([
            'final_exam_id' => $exam1->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'passing_score' => 70.00,
            'raw_score' => 100.00,
            'percentage_score' => 100.00,
            'is_passed' => true,
            'status' => 'passed',
            'submitted_at' => now(),
            'graded_at' => now(),
        ]);

        // Exam 2 waiting_review (graded_at is null)
        FinalExamAttempt::create([
            'final_exam_id' => $exam2->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'passing_score' => 70.00,
            'raw_score' => 0.00,
            'percentage_score' => 0.00,
            'is_passed' => null,
            'status' => 'waiting_review',
            'submitted_at' => now(),
            'graded_at' => null,
        ]);

        $cert = $this->certificateService->evaluateAndCreateForEnrollment($this->enrollment);
        $this->assertNull($cert);
        $this->assertEquals('active', $this->enrollment->fresh()->status);
    }

    public function test_certificate_issued_for_mixed_pass_fail_and_score_only_sections()
    {
        $exam1 = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Pass Fail Section',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        FinalExamQuestion::create(['final_exam_id' => $exam1->id, 'question_type' => 'multiple_choice', 'question' => 'Q1', 'score' => 100.00, 'is_active' => true]);

        $exam2 = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Score Only Section',
            'result_mode' => AssessmentResultMode::SCORE_ONLY,
            'total_score' => 50.00,
            'passing_score' => null,
            'is_active' => true,
        ]);

        FinalExamQuestion::create(['final_exam_id' => $exam2->id, 'question_type' => 'multiple_choice', 'question' => 'Q2', 'score' => 50.00, 'is_active' => true]);

        FinalExamAttempt::create([
            'final_exam_id' => $exam1->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'passing_score' => 70.00,
            'raw_score' => 80.00,
            'percentage_score' => 80.00,
            'is_passed' => true,
            'status' => 'passed',
            'submitted_at' => now(),
            'graded_at' => now(),
        ]);

        FinalExamAttempt::create([
            'final_exam_id' => $exam2->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'max_score' => 50.00,
            'result_mode' => 'score_only',
            'passing_score' => null,
            'raw_score' => 25.00,
            'percentage_score' => 50.00,
            'is_passed' => null,
            'status' => 'submitted',
            'submitted_at' => now(),
            'graded_at' => now(),
        ]);

        $cert = $this->certificateService->evaluateAndCreateForEnrollment($this->enrollment);
        $this->assertNotNull($cert);
        $this->assertEquals(65.00, (float) $cert->final_score); // (80 + 50) / 2 = 65.00

        $snapshots = $cert->section_scores;
        $this->assertCount(2, $snapshots);
        $this->assertNull($snapshots[1]['passing_score']);
        $this->assertNull($snapshots[1]['is_passed']);
    }

    public function test_certificate_idempotency_duplicate_call_returns_existing()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Exam 1',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        FinalExamQuestion::create(['final_exam_id' => $exam->id, 'question_type' => 'multiple_choice', 'question' => 'Q1', 'score' => 100.00, 'is_active' => true]);

        FinalExamAttempt::create([
            'final_exam_id' => $exam->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'passing_score' => 70.00,
            'raw_score' => 90.00,
            'percentage_score' => 90.00,
            'is_passed' => true,
            'status' => 'passed',
            'submitted_at' => now(),
            'graded_at' => now(),
        ]);

        $cert1 = $this->certificateService->evaluateAndCreateForEnrollment($this->enrollment);
        $cert2 = $this->certificateService->evaluateAndCreateForEnrollment($this->enrollment);

        $this->assertNotNull($cert1);
        $this->assertEquals($cert1->id, $cert2->id);
        $this->assertEquals(1, Certificate::where('enrollment_id', $this->enrollment->id)->count());
    }

    public function test_attempt_selection_highest_percentage_score()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Exam 1',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        FinalExamQuestion::create(['final_exam_id' => $exam->id, 'question_type' => 'multiple_choice', 'question' => 'Q1', 'score' => 100.00, 'is_active' => true]);

        // Attempt 1: 70%
        FinalExamAttempt::create([
            'final_exam_id' => $exam->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'passing_score' => 70.00,
            'raw_score' => 70.00,
            'percentage_score' => 70.00,
            'is_passed' => true,
            'status' => 'passed',
            'submitted_at' => now()->subHour(),
            'graded_at' => now()->subHour(),
        ]);

        // Attempt 2: 95%
        $attempt2 = FinalExamAttempt::create([
            'final_exam_id' => $exam->id,
            'student_id' => $this->student->id,
            'attempt_number' => 2,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'passing_score' => 70.00,
            'raw_score' => 95.00,
            'percentage_score' => 95.00,
            'is_passed' => true,
            'status' => 'passed',
            'submitted_at' => now(),
            'graded_at' => now(),
        ]);

        $cert = $this->certificateService->evaluateAndCreateForEnrollment($this->enrollment);
        $this->assertNotNull($cert);
        $this->assertEquals(95.00, (float) $cert->final_score);
        $this->assertEquals($attempt2->id, $cert->section_scores[0]['attempt_id']);
    }

    public function test_module_practice_progress_pass_fail_unlocks_on_passed_only()
    {
        $practice = ModulePractice::create([
            'module_id' => $this->module1->id,
            'title' => 'Required Practice 1',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        // Attempt 1: Failed
        ModulePracticeAttempt::create([
            'module_practice_id' => $practice->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'passing_score' => 70.00,
            'raw_score' => 50.00,
            'percentage_score' => 50.00,
            'is_passed' => false,
            'status' => 'failed',
            'submitted_at' => now(),
            'graded_at' => now(),
        ]);

        $completed = $this->progressService->evaluateAndSyncModuleCompletion($this->enrollment, $this->module1);
        $this->assertFalse($completed);
        $this->assertFalse(StudentModuleProgress::where('enrollment_id', $this->enrollment->id)->where('module_id', $this->module1->id)->where('status', 'completed')->exists());

        // Attempt 2: Passed
        ModulePracticeAttempt::create([
            'module_practice_id' => $practice->id,
            'student_id' => $this->student->id,
            'attempt_number' => 2,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'passing_score' => 70.00,
            'raw_score' => 80.00,
            'percentage_score' => 80.00,
            'is_passed' => true,
            'status' => 'passed',
            'submitted_at' => now(),
            'graded_at' => now(),
        ]);

        $completed2 = $this->progressService->evaluateAndSyncModuleCompletion($this->enrollment, $this->module1);
        $this->assertTrue($completed2);
        $this->assertTrue(StudentModuleProgress::where('enrollment_id', $this->enrollment->id)->where('module_id', $this->module1->id)->where('status', 'completed')->exists());
    }

    public function test_module_practice_progress_score_only_unlocks_on_submitted_graded()
    {
        $practice = ModulePractice::create([
            'module_id' => $this->module1->id,
            'title' => 'Score Only Practice',
            'result_mode' => AssessmentResultMode::SCORE_ONLY,
            'total_score' => 50.00,
            'passing_score' => null,
            'is_active' => true,
        ]);

        ModulePracticeAttempt::create([
            'module_practice_id' => $practice->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'max_score' => 50.00,
            'result_mode' => 'score_only',
            'passing_score' => null,
            'raw_score' => 20.00,
            'percentage_score' => 40.00,
            'is_passed' => null,
            'status' => 'submitted',
            'submitted_at' => now(),
            'graded_at' => now(),
        ]);

        $completed = $this->progressService->evaluateAndSyncModuleCompletion($this->enrollment, $this->module1);
        $this->assertTrue($completed);
        $this->assertTrue(StudentModuleProgress::where('enrollment_id', $this->enrollment->id)->where('module_id', $this->module1->id)->where('status', 'completed')->exists());
    }

    public function test_waiting_review_practice_does_not_complete_module()
    {
        $practice = ModulePractice::create([
            'module_id' => $this->module1->id,
            'title' => 'Essay Practice',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        ModulePracticeAttempt::create([
            'module_practice_id' => $practice->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'max_score' => 100.00,
            'result_mode' => 'pass_fail',
            'passing_score' => 70.00,
            'raw_score' => 40.00,
            'percentage_score' => 40.00,
            'is_passed' => null,
            'status' => 'waiting_review',
            'submitted_at' => now(),
            'graded_at' => null,
        ]);

        $completed = $this->progressService->evaluateAndSyncModuleCompletion($this->enrollment, $this->module1);
        $this->assertFalse($completed);
        $this->assertFalse(StudentModuleProgress::where('enrollment_id', $this->enrollment->id)->where('module_id', $this->module1->id)->where('status', 'completed')->exists());
    }
}
