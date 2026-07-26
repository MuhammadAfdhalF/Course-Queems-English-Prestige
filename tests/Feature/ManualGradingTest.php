<?php

namespace Tests\Feature;

use App\Enums\AssessmentResultMode;
use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\FinalExam;
use App\Models\FinalExamAnswer;
use App\Models\FinalExamAttempt;
use App\Models\FinalExamQuestion;
use App\Models\Module;
use App\Models\ModulePractice;
use App\Models\ModulePracticeAnswer;
use App\Models\ModulePracticeAttempt;
use App\Models\ModulePracticeQuestion;
use App\Models\StudentCourseEnrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ManualGradingTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected User $student;
    protected CourseProgram $program;
    protected CourseLevel $level;
    protected Module $module;
    protected StudentCourseEnrollment $enrollment;

    protected function setUp(): void
    {
        parent::setUp();

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
            'name' => 'Manual Grading Program ' . $uid,
            'slug' => 'manual-grading-program-' . $uid,
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

        $this->enrollment = StudentCourseEnrollment::create([
            'student_id' => $this->student->id,
            'course_level_id' => $this->level->id,
            'status' => 'active',
            'progress_percentage' => 100.00,
        ]);
    }

    public function test_final_exam_manual_grading_pass_fail_successful_finalization()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Mixed Exam',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        $qAuto = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'multiple_choice',
            'question' => 'Auto Q1',
            'score' => 40.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $qAuto->options()->createMany([
            ['option_label' => 'A', 'option_text' => 'Correct', 'is_correct' => true, 'sort_order' => 1],
            ['option_label' => 'B', 'option_text' => 'Wrong', 'is_correct' => false, 'sort_order' => 2],
        ]);

        $qManual = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'essay',
            'question' => 'Essay Q2',
            'score' => 60.00,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $optCorrect = $qAuto->options->where('is_correct', true)->first();

        // Submit initial attempt -> waiting_review
        $this->actingAs($this->student)
            ->post(route('student.final-exam.submit', [
                'enrollment' => $this->enrollment->id,
                'finalExam' => $exam->id,
            ]), [
                'answers' => [
                    $qAuto->id => $optCorrect->id,
                    $qManual->id => 'Student essay answer text',
                ],
            ]);

        $attempt = FinalExamAttempt::where('final_exam_id', $exam->id)->first();
        $this->assertNotNull($attempt);
        $this->assertEquals('waiting_review', $attempt->status);
        $this->assertNull($attempt->is_passed);
        $this->assertNull($attempt->graded_at);
        $submittedAtOriginal = $attempt->submitted_at;

        $manualAnswer = $attempt->answers->where('final_exam_question_id', $qManual->id)->first();

        // Admin grades manual question with 35.00 points (Total = 40 + 35 = 75.00 >= 70.00 -> passed)
        $response = $this->actingAs($this->admin)
            ->put(route('admin.course-management.final-exam-reviews.update', $attempt->id), [
                'answers' => [
                    $manualAnswer->id => [
                        'score' => 35.00,
                        'feedback' => 'Good essay effort.',
                    ],
                ],
            ]);

        $response->assertRedirect();

        $attempt->refresh();
        $this->assertEquals(75.00, (float) $attempt->raw_score);
        $this->assertEquals(75.00, (float) $attempt->percentage_score);
        $this->assertTrue((bool) $attempt->is_passed);
        $this->assertEquals('passed', $attempt->status);
        $this->assertNotNull($attempt->graded_at);
        $this->assertEquals($submittedAtOriginal->toDateTimeString(), $attempt->submitted_at->toDateTimeString());
    }

    public function test_final_exam_manual_grading_exact_threshold_evaluated_as_passed()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Threshold Exam',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        $qManual = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'essay',
            'question' => 'Essay Q1',
            'score' => 100.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($this->student)
            ->post(route('student.final-exam.submit', [
                'enrollment' => $this->enrollment->id,
                'finalExam' => $exam->id,
            ]), [
                'answers' => [
                    $qManual->id => 'Essay response',
                ],
            ]);

        $attempt = FinalExamAttempt::where('final_exam_id', $exam->id)->first();
        $manualAnswer = $attempt->answers->first();

        // Admin grades exactly 70.00 points == passing_score 70.00
        $this->actingAs($this->admin)
            ->put(route('admin.course-management.final-exam-reviews.update', $attempt->id), [
                'answers' => [
                    $manualAnswer->id => [
                        'score' => 70.00,
                    ],
                ],
            ]);

        $attempt->refresh();
        $this->assertEquals(70.00, (float) $attempt->raw_score);
        $this->assertTrue((bool) $attempt->is_passed);
        $this->assertEquals('passed', $attempt->status);
    }

    public function test_final_exam_manual_grading_score_only_mode()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Score Only Exam',
            'result_mode' => AssessmentResultMode::SCORE_ONLY,
            'total_score' => 50.00,
            'passing_score' => null,
            'is_active' => true,
        ]);

        $qManual = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'essay',
            'question' => 'Essay Q1',
            'score' => 50.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($this->student)
            ->post(route('student.final-exam.submit', [
                'enrollment' => $this->enrollment->id,
                'finalExam' => $exam->id,
            ]), [
                'answers' => [
                    $qManual->id => 'Essay response',
                ],
            ]);

        $attempt = FinalExamAttempt::where('final_exam_id', $exam->id)->first();
        $manualAnswer = $attempt->answers->first();

        $this->actingAs($this->admin)
            ->put(route('admin.course-management.final-exam-reviews.update', $attempt->id), [
                'answers' => [
                    $manualAnswer->id => [
                        'score' => 45.00,
                    ],
                ],
            ]);

        $attempt->refresh();
        $this->assertEquals(45.00, (float) $attempt->raw_score);
        $this->assertNull($attempt->is_passed);
        $this->assertEquals('submitted', $attempt->status);
        $this->assertNotNull($attempt->graded_at);
    }

    public function test_manual_grading_zero_score_is_valid()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Zero Grade Exam',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 50.00,
            'passing_score' => 30.00,
            'is_active' => true,
        ]);

        $qManual = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'essay',
            'question' => 'Essay Q1',
            'score' => 50.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($this->student)
            ->post(route('student.final-exam.submit', [
                'enrollment' => $this->enrollment->id,
                'finalExam' => $exam->id,
            ]), [
                'answers' => [
                    $qManual->id => 'Off topic essay response',
                ],
            ]);

        $attempt = FinalExamAttempt::where('final_exam_id', $exam->id)->first();
        $manualAnswer = $attempt->answers->first();

        // Admin grades 0.00
        $this->actingAs($this->admin)
            ->put(route('admin.course-management.final-exam-reviews.update', $attempt->id), [
                'answers' => [
                    $manualAnswer->id => [
                        'score' => 0.00,
                        'feedback' => 'Off topic.',
                    ],
                ],
            ]);

        $attempt->refresh();
        $this->assertEquals(0.00, (float) $attempt->raw_score);
        $this->assertFalse((bool) $attempt->is_passed);
        $this->assertEquals('failed', $attempt->status);
        $this->assertNotNull($attempt->graded_at);
    }

    public function test_manual_grading_score_exceeding_question_score_rejected()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Max Guard Exam',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 20.00,
            'passing_score' => 10.00,
            'is_active' => true,
        ]);

        $qManual = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'essay',
            'question' => 'Essay Q1',
            'score' => 20.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($this->student)
            ->post(route('student.final-exam.submit', [
                'enrollment' => $this->enrollment->id,
                'finalExam' => $exam->id,
            ]), [
                'answers' => [
                    $qManual->id => 'Response',
                ],
            ]);

        $attempt = FinalExamAttempt::where('final_exam_id', $exam->id)->first();
        $manualAnswer = $attempt->answers->first();

        // Admin tries to award 25.00 (Max is 20.00)
        $response = $this->actingAs($this->admin)
            ->put(route('admin.course-management.final-exam-reviews.update', $attempt->id), [
                'answers' => [
                    $manualAnswer->id => [
                        'score' => 25.00,
                    ],
                ],
            ]);

        $response->assertSessionHasErrors();
        $attempt->refresh();
        $this->assertEquals('waiting_review', $attempt->status);
        $this->assertNull($attempt->graded_at);
    }

    public function test_idempotency_double_finalization_rejected()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Double Finalize Exam',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        $qManual = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'essay',
            'question' => 'Essay Q1',
            'score' => 100.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($this->student)
            ->post(route('student.final-exam.submit', [
                'enrollment' => $this->enrollment->id,
                'finalExam' => $exam->id,
            ]), [
                'answers' => [
                    $qManual->id => 'Response',
                ],
            ]);

        $attempt = FinalExamAttempt::where('final_exam_id', $exam->id)->first();
        $manualAnswer = $attempt->answers->first();

        // 1st finalize
        $this->actingAs($this->admin)
            ->put(route('admin.course-management.final-exam-reviews.update', $attempt->id), [
                'answers' => [
                    $manualAnswer->id => [
                        'score' => 80.00,
                    ],
                ],
            ]);

        $attempt->refresh();
        $this->assertEquals('passed', $attempt->status);
        $firstGradedAt = $attempt->graded_at;

        // 2nd finalize attempt (should be rejected by idempotency check)
        $response = $this->actingAs($this->admin)
            ->put(route('admin.course-management.final-exam-reviews.update', $attempt->id), [
                'answers' => [
                    $manualAnswer->id => [
                        'score' => 10.00, // Try to change score to fail
                    ],
                ],
            ]);

        $response->assertRedirect();
        $attempt->refresh();
        $this->assertEquals(80.00, (float) $attempt->raw_score);
        $this->assertEquals('passed', $attempt->status);
        $this->assertEquals($firstGradedAt->toDateTimeString(), $attempt->graded_at->toDateTimeString());
    }

    public function test_module_practice_manual_grading_parity()
    {
        $practice = ModulePractice::create([
            'module_id' => $this->module->id,
            'title' => 'Manual Practice',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 50.00,
            'passing_score' => 30.00,
            'is_active' => true,
        ]);

        $qManual = ModulePracticeQuestion::create([
            'module_practice_id' => $practice->id,
            'question_type' => 'short_answer',
            'question' => 'Short Answer Q1',
            'score' => 50.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($this->student)
            ->post(route('student.module-practice.submit', [
                'enrollment' => $this->enrollment->id,
                'module' => $this->module->id,
                'practice' => $practice->id,
            ]), [
                'answers' => [
                    $qManual->id => 'Student short answer',
                ],
            ]);

        $attempt = ModulePracticeAttempt::where('module_practice_id', $practice->id)->first();
        $this->assertEquals('waiting_review', $attempt->status);
        $manualAnswer = $attempt->answers->first();

        // Admin grades 35.00 -> passed
        $response = $this->actingAs($this->admin)
            ->put(route('admin.course-management.practice-reviews.update', $attempt->id), [
                'answers' => [
                    $manualAnswer->id => [
                        'score' => 35.00,
                        'feedback' => 'Good short answer.',
                    ],
                ],
            ]);

        $response->assertRedirect();

        $attempt->refresh();
        $this->assertEquals(35.00, (float) $attempt->raw_score);
        $this->assertTrue((bool) $attempt->is_passed);
        $this->assertEquals('passed', $attempt->status);
        $this->assertNotNull($attempt->graded_at);
    }
}
