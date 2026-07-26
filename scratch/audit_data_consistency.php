<?php

use App\Models\FinalExam;
use App\Models\FinalExamAttempt;
use App\Models\FreeTest;
use App\Models\FreeTestResult;
use App\Models\ModulePractice;
use App\Models\ModulePracticeAttempt;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- DATA CONSISTENCY AUDIT ---\n";

$invalidExams = FinalExam::query()
    ->where('total_score', '<=', 0)
    ->orWhere(function ($q) {
        $q->where('result_mode', 'pass_fail')
            ->where(function ($sub) {
                $sub->whereNull('passing_score')
                    ->orWhere('passing_score', '<=', 0)
                    ->orWhereRaw('passing_score > total_score');
            });
    })
    ->orWhere(function ($q) {
        $q->where('result_mode', 'score_only')
            ->whereNotNull('passing_score');
    })
    ->count();

$invalidPractices = ModulePractice::query()
    ->where('total_score', '<=', 0)
    ->orWhere(function ($q) {
        $q->where('result_mode', 'pass_fail')
            ->where(function ($sub) {
                $sub->whereNull('passing_score')
                    ->orWhere('passing_score', '<=', 0)
                    ->orWhereRaw('passing_score > total_score');
            });
    })
    ->orWhere(function ($q) {
        $q->where('result_mode', 'score_only')
            ->whereNotNull('passing_score');
    })
    ->count();

$invalidFreeTests = FreeTest::query()
    ->where('total_score', '<=', 0)
    ->orWhere(function ($q) {
        $q->where('result_mode', 'pass_fail')
            ->where(function ($sub) {
                $sub->whereNull('passing_score')
                    ->orWhere('passing_score', '<=', 0)
                    ->orWhereRaw('passing_score > total_score');
            });
    })
    ->orWhere(function ($q) {
        $q->where('result_mode', 'score_only')
            ->whereNotNull('passing_score');
    })
    ->count();

echo "Invalid FinalExams: {$invalidExams}\n";
echo "Invalid ModulePractices: {$invalidPractices}\n";
echo "Invalid FreeTests: {$invalidFreeTests}\n";

$invalidExamAttempts = FinalExamAttempt::query()
    ->where('max_score', '<=', 0)
    ->orWhereRaw('raw_score > max_score')
    ->orWhere('percentage_score', '<', 0)
    ->orWhere('percentage_score', '>', 100)
    ->count();

$invalidPracticeAttempts = ModulePracticeAttempt::query()
    ->where('max_score', '<=', 0)
    ->orWhereRaw('raw_score > max_score')
    ->orWhere('percentage_score', '<', 0)
    ->orWhere('percentage_score', '>', 100)
    ->count();

$invalidFreeTestResults = FreeTestResult::query()
    ->where('max_score', '<=', 0)
    ->orWhereRaw('raw_score > max_score')
    ->orWhere('percentage_score', '<', 0)
    ->orWhere('percentage_score', '>', 100)
    ->count();

echo "Invalid FinalExamAttempts: {$invalidExamAttempts}\n";
echo "Invalid ModulePracticeAttempts: {$invalidPracticeAttempts}\n";
echo "Invalid FreeTestResults: {$invalidFreeTestResults}\n";

echo "Has passing_grade column in final_exams: " . (Schema::hasColumn('final_exams', 'passing_grade') ? 'YES' : 'NO') . "\n";
echo "Has passing_grade column in module_practices: " . (Schema::hasColumn('module_practices', 'passing_grade') ? 'YES' : 'NO') . "\n";
echo "Has passing_grade column in free_tests: " . (Schema::hasColumn('free_tests', 'passing_grade') ? 'YES' : 'NO') . "\n";

echo "--- AUDIT COMPLETED ---\n";
