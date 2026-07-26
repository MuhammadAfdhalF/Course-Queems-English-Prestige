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
use App\Models\StudentCourseEnrollment;
use App\Models\User;
use App\Services\AssessmentScoringService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StudentAttemptScoringTest extends TestCase
{
    use DatabaseTransactions;

    protected User $student;
    protected CourseProgram $program;
    protected CourseLevel $level;
    protected Module $module;
    protected StudentCourseEnrollment $enrollment;
    protected AssessmentScoringService $scoringService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $uid = uniqid();
        $this->program = CourseProgram::create([
            'name' => 'Scoring Program ' . $uid,
            'slug' => 'scoring-program-' . $uid,
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

        $this->scoringService = app(AssessmentScoringService::class);
    }

    public function test_final_exam_attempt_snapshot_creation_and_pass_fail_submission()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Pass Fail Exam',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        $q1 = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'multiple_choice',
            'question' => 'Question 1',
            'score' => 70.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $q1->options()->createMany([
            ['option_label' => 'A', 'option_text' => 'Correct Option', 'is_correct' => true, 'sort_order' => 1],
            ['option_label' => 'B', 'option_text' => 'Wrong Option', 'is_correct' => false, 'sort_order' => 2],
        ]);

        $q2 = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'multiple_choice',
            'question' => 'Question 2',
            'score' => 30.00,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $q2->options()->createMany([
            ['option_label' => 'A', 'option_text' => 'Correct Option', 'is_correct' => true, 'sort_order' => 1],
            ['option_label' => 'B', 'option_text' => 'Wrong Option', 'is_correct' => false, 'sort_order' => 2],
        ]);

        $opt1Correct = $q1->options->where('is_correct', true)->first();
        $opt2Wrong = $q2->options->where('is_correct', false)->first();

        $response = $this->actingAs($this->student)
            ->post(route('student.final-exam.submit', [
                'enrollment' => $this->enrollment->id,
                'finalExam' => $exam->id,
            ]), [
                'answers' => [
                    $q1->id => $opt1Correct->id,
                    $q2->id => $opt2Wrong->id,
                ],
            ]);

        $response->assertRedirect();

        $attempt = FinalExamAttempt::where('final_exam_id', $exam->id)->first();
        $this->assertNotNull($attempt);
        $this->assertEquals(100.00, (float) $attempt->max_score);
        $this->assertEquals(70.00, (float) $attempt->passing_score);
        $this->assertEquals('pass_fail', $attempt->result_mode->value);
        $this->assertEquals(70.00, (float) $attempt->raw_score);
        $this->assertEquals(70.00, (float) $attempt->percentage_score);
        $this->assertTrue((bool) $attempt->is_passed);
        $this->assertEquals('passed', $attempt->status);
    }

    public function test_final_exam_score_only_submission()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Score Only Exam',
            'result_mode' => AssessmentResultMode::SCORE_ONLY,
            'total_score' => 50.00,
            'passing_score' => null,
            'is_active' => true,
        ]);

        $q1 = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'multiple_choice',
            'question' => 'Question 1',
            'score' => 50.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $q1->options()->createMany([
            ['option_label' => 'A', 'option_text' => 'Correct Option', 'is_correct' => true, 'sort_order' => 1],
            ['option_label' => 'B', 'option_text' => 'Wrong Option', 'is_correct' => false, 'sort_order' => 2],
        ]);

        $optWrong = $q1->options->where('is_correct', false)->first();

        $this->actingAs($this->student)
            ->post(route('student.final-exam.submit', [
                'enrollment' => $this->enrollment->id,
                'finalExam' => $exam->id,
            ]), [
                'answers' => [
                    $q1->id => $optWrong->id,
                ],
            ]);

        $attempt = FinalExamAttempt::where('final_exam_id', $exam->id)->first();
        $this->assertNotNull($attempt);
        $this->assertEquals(50.00, (float) $attempt->max_score);
        $this->assertNull($attempt->passing_score);
        $this->assertEquals('score_only', $attempt->result_mode->value);
        $this->assertEquals(0.00, (float) $attempt->raw_score);
        $this->assertEquals(0.00, (float) $attempt->percentage_score);
        $this->assertNull($attempt->is_passed);
        $this->assertEquals('submitted', $attempt->status);
    }

    public function test_final_exam_manual_review_sets_waiting_review()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Essay Exam',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 60.00,
            'is_active' => true,
        ]);

        $q1 = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'essay',
            'question' => 'Essay Question 1',
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
                    $q1->id => 'This is my essay response.',
                ],
            ]);

        $attempt = FinalExamAttempt::where('final_exam_id', $exam->id)->first();
        $this->assertNotNull($attempt);
        $this->assertEquals('waiting_review', $attempt->status);
        $this->assertNull($attempt->is_passed);
        $this->assertNull($attempt->graded_at);
    }

    public function test_snapshot_immutability_uses_attempt_snapshot_over_updated_master()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Immutability Exam',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'is_active' => true,
        ]);

        $q1 = FinalExamQuestion::create([
            'final_exam_id' => $exam->id,
            'question_type' => 'multiple_choice',
            'question' => 'Question 1',
            'score' => 100.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $q1->options()->createMany([
            ['option_label' => 'A', 'option_text' => 'Correct Option', 'is_correct' => true, 'sort_order' => 1],
            ['option_label' => 'B', 'option_text' => 'Wrong Option', 'is_correct' => false, 'sort_order' => 2],
        ]);

        $optCorrect = $q1->options->where('is_correct', true)->first();

        // Submit attempt with passing_score = 70.00
        $this->actingAs($this->student)
            ->post(route('student.final-exam.submit', [
                'enrollment' => $this->enrollment->id,
                'finalExam' => $exam->id,
            ]), [
                'answers' => [
                    $q1->id => $optCorrect->id,
                ],
            ]);

        $attempt = FinalExamAttempt::where('final_exam_id', $exam->id)->first();
        $this->assertEquals(70.00, (float) $attempt->passing_score);
        $this->assertTrue((bool) $attempt->is_passed);

        // Even if master assessment is updated directly on database to passing_score = 99.00
        $exam->update(['passing_score' => 99.00]);

        $attempt->refresh();
        // The attempt snapshot MUST remain 70.00 and is_passed MUST remain true
        $this->assertEquals(70.00, (float) $attempt->passing_score);
        $this->assertTrue((bool) $attempt->is_passed);
    }

    public function test_free_test_public_submission()
    {
        $freeTest = FreeTest::create([
            'title' => 'Public Free Test',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 10.00,
            'passing_score' => 8.00,
            'is_active' => true,
        ]);

        FreeTestQuestion::create([
            'free_test_id' => $freeTest->id,
            'question_type' => 'multiple_choice',
            'question' => 'Free Q1',
            'option_a' => 'Option A',
            'option_b' => 'Option B',
            'option_c' => 'Option C',
            'option_d' => 'Option D',
            'correct_answer' => 'A',
            'score' => 10.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $q = $freeTest->questions->first();

        $response = $this->post(route('free-test.submit', $freeTest->id), [
            'participant_name' => 'John Doe',
            'participant_email' => 'john@example.com',
            'participant_whatsapp' => '08123456789',
            'answers' => [
                $q->id => 'A',
            ],
        ]);

        $response->assertRedirect();

        $result = FreeTestResult::where('free_test_id', $freeTest->id)->first();
        $this->assertNotNull($result);
        $this->assertEquals(10.00, (float) $result->max_score);
        $this->assertEquals(8.00, (float) $result->passing_score);
        $this->assertEquals(10.00, (float) $result->raw_score);
        $this->assertTrue((bool) $result->is_passed);
    }

    public function test_module_practice_attempt_snapshot_and_submission()
    {
        $practice = ModulePractice::create([
            'module_id' => $this->module->id,
            'title' => 'Practice 1',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 75.00,
            'is_active' => true,
        ]);

        $q = ModulePracticeQuestion::create([
            'module_practice_id' => $practice->id,
            'question_type' => 'multiple_choice',
            'question' => 'Practice Question 1',
            'score' => 100.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $q->options()->createMany([
            ['option_label' => 'A', 'option_text' => 'Option A', 'is_correct' => true, 'sort_order' => 1],
            ['option_label' => 'B', 'option_text' => 'Option B', 'is_correct' => false, 'sort_order' => 2],
        ]);

        $optCorrect = $q->options->where('is_correct', true)->first();

        $response = $this->actingAs($this->student)
            ->post(route('student.module-practice.submit', [
                'enrollment' => $this->enrollment->id,
                'module' => $this->module->id,
                'practice' => $practice->id,
            ]), [
                'answers' => [
                    $q->id => $optCorrect->id,
                ],
            ]);

        $response->assertRedirect();

        $attempt = ModulePracticeAttempt::where('module_practice_id', $practice->id)->first();
        $this->assertNotNull($attempt);
        $this->assertEquals(100.00, (float) $attempt->max_score);
        $this->assertEquals(75.00, (float) $attempt->passing_score);
        $this->assertEquals(100.00, (float) $attempt->raw_score);
        $this->assertTrue((bool) $attempt->is_passed);
        $this->assertEquals('passed', $attempt->status);
    }

    public function test_max_attempts_limit_blocks_new_submissions()
    {
        $exam = FinalExam::create([
            'course_level_id' => $this->level->id,
            'title' => 'Limited Attempts Exam',
            'result_mode' => AssessmentResultMode::PASS_FAIL,
            'total_score' => 100.00,
            'passing_score' => 70.00,
            'max_attempts' => 1,
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

        $q->options()->createMany([
            ['option_label' => 'A', 'option_text' => 'Correct', 'is_correct' => true, 'sort_order' => 1],
            ['option_label' => 'B', 'option_text' => 'Wrong', 'is_correct' => false, 'sort_order' => 2],
        ]);

        $optWrong = $q->options->where('is_correct', false)->first();

        // 1st attempt (fails)
        $this->actingAs($this->student)
            ->post(route('student.final-exam.submit', [
                'enrollment' => $this->enrollment->id,
                'finalExam' => $exam->id,
            ]), [
                'answers' => [
                    $q->id => $optWrong->id,
                ],
            ]);

        $this->assertEquals(1, FinalExamAttempt::where('final_exam_id', $exam->id)->count());

        // 2nd attempt should be rejected because max_attempts = 1
        $response = $this->actingAs($this->student)
            ->post(route('student.final-exam.submit', [
                'enrollment' => $this->enrollment->id,
                'finalExam' => $exam->id,
            ]), [
                'answers' => [
                    $q->id => $optWrong->id,
                ],
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(1, FinalExamAttempt::where('final_exam_id', $exam->id)->count());
    }
}
