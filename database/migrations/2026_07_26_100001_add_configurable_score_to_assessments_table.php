<?php

use App\Enums\AssessmentResultMode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add columns (total_score as nullable temporarily for backfill)
        Schema::table('final_exams', function (Blueprint $table) {
            $table->decimal('total_score', 8, 2)->nullable()->after('description');
            $table->string('result_mode', 20)->default(AssessmentResultMode::PASS_FAIL->value)->after('total_score');
            $table->decimal('passing_score', 8, 2)->nullable()->after('result_mode');
        });

        Schema::table('module_practices', function (Blueprint $table) {
            $table->decimal('total_score', 8, 2)->nullable()->after('description');
            $table->string('result_mode', 20)->default(AssessmentResultMode::PASS_FAIL->value)->after('total_score');
            $table->decimal('passing_score', 8, 2)->nullable()->after('result_mode');
        });

        Schema::table('free_tests', function (Blueprint $table) {
            $table->decimal('total_score', 8, 2)->nullable()->after('description');
            $table->string('result_mode', 20)->default(AssessmentResultMode::SCORE_ONLY->value)->after('total_score');
            $table->decimal('passing_score', 8, 2)->nullable()->after('result_mode');
        });

        // 2. Perform Master Backfill
        $this->backfillFinalExams();
        $this->backfillModulePractices();
        $this->backfillFreeTests();

        // 3. Make total_score NOT NULL (no permanent DB default)
        Schema::table('final_exams', function (Blueprint $table) {
            $table->decimal('total_score', 8, 2)->nullable(false)->change();
        });

        Schema::table('module_practices', function (Blueprint $table) {
            $table->decimal('total_score', 8, 2)->nullable(false)->change();
        });

        Schema::table('free_tests', function (Blueprint $table) {
            $table->decimal('total_score', 8, 2)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('final_exams', function (Blueprint $table) {
            $table->dropColumn(['total_score', 'result_mode', 'passing_score']);
        });

        Schema::table('module_practices', function (Blueprint $table) {
            $table->dropColumn(['total_score', 'result_mode', 'passing_score']);
        });

        Schema::table('free_tests', function (Blueprint $table) {
            $table->dropColumn(['total_score', 'result_mode', 'passing_score']);
        });
    }

    private function backfillFinalExams(): void
    {
        $exams = DB::table('final_exams')->get();

        foreach ($exams as $exam) {
            $sumActiveScore = (float) DB::table('final_exam_questions')
                ->where('final_exam_id', $exam->id)
                ->where('is_active', true)
                ->sum('score');

            $passingGrade = (int) $exam->passing_grade;

            if ($sumActiveScore > 0 && $passingGrade > 0) {
                $totalScore = round($sumActiveScore, 2);
                $resultMode = AssessmentResultMode::PASS_FAIL->value;
                $passingScore = round(($passingGrade / 100) * $totalScore, 2);
                $isActive = (bool) $exam->is_active;
            } elseif ($passingGrade <= 0) {
                $totalScore = $sumActiveScore > 0 ? round($sumActiveScore, 2) : 100.00;
                $resultMode = AssessmentResultMode::SCORE_ONLY->value;
                $passingScore = null;
                $isActive = false; // Set inactive for Admin review
            } else {
                // Zero active questions or sum = 0
                $totalScore = 100.00;
                $resultMode = AssessmentResultMode::PASS_FAIL->value;
                $passingScore = null;
                $isActive = false; // Needs Configuration
            }

            DB::table('final_exams')
                ->where('id', $exam->id)
                ->update([
                    'total_score' => $totalScore,
                    'result_mode' => $resultMode,
                    'passing_score' => $passingScore,
                    'is_active' => $isActive,
                ]);
        }
    }

    private function backfillModulePractices(): void
    {
        $practices = DB::table('module_practices')->get();

        foreach ($practices as $practice) {
            $sumActiveScore = (float) DB::table('module_practice_questions')
                ->where('module_practice_id', $practice->id)
                ->where('is_active', true)
                ->sum('score');

            $passingGrade = (int) $practice->passing_grade;

            if ($sumActiveScore > 0 && $passingGrade > 0) {
                $totalScore = round($sumActiveScore, 2);
                $resultMode = AssessmentResultMode::PASS_FAIL->value;
                $passingScore = round(($passingGrade / 100) * $totalScore, 2);
                $isActive = (bool) $practice->is_active;
            } elseif ($passingGrade <= 0) {
                $totalScore = $sumActiveScore > 0 ? round($sumActiveScore, 2) : 100.00;
                $resultMode = AssessmentResultMode::SCORE_ONLY->value;
                $passingScore = null;
                $isActive = false;
            } else {
                $totalScore = 100.00;
                $resultMode = AssessmentResultMode::PASS_FAIL->value;
                $passingScore = null;
                $isActive = false;
            }

            DB::table('module_practices')
                ->where('id', $practice->id)
                ->update([
                    'total_score' => $totalScore,
                    'result_mode' => $resultMode,
                    'passing_score' => $passingScore,
                    'is_active' => $isActive,
                ]);
        }
    }

    private function backfillFreeTests(): void
    {
        $freeTests = DB::table('free_tests')->get();

        foreach ($freeTests as $freeTest) {
            $sumActiveScore = (float) DB::table('free_test_questions')
                ->where('free_test_id', $freeTest->id)
                ->where('is_active', true)
                ->sum('score');

            $passingGrade = (int) $freeTest->passing_grade;

            if ($sumActiveScore > 0 && $passingGrade > 0) {
                $totalScore = round($sumActiveScore, 2);
                $resultMode = AssessmentResultMode::PASS_FAIL->value;
                $passingScore = round(($passingGrade / 100) * $totalScore, 2);
                $isActive = (bool) $freeTest->is_active;
            } else {
                $totalScore = $sumActiveScore > 0 ? round($sumActiveScore, 2) : 100.00;
                $resultMode = AssessmentResultMode::SCORE_ONLY->value;
                $passingScore = null;
                $isActive = $sumActiveScore > 0 ? (bool) $freeTest->is_active : false;
            }

            DB::table('free_tests')
                ->where('id', $freeTest->id)
                ->update([
                    'total_score' => $totalScore,
                    'result_mode' => $resultMode,
                    'passing_score' => $passingScore,
                    'is_active' => $isActive,
                ]);
        }
    }
};
