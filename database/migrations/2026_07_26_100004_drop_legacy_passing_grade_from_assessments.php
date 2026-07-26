<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('final_exams', function (Blueprint $table) {
            if (Schema::hasColumn('final_exams', 'passing_grade')) {
                $table->dropColumn('passing_grade');
            }
        });

        Schema::table('module_practices', function (Blueprint $table) {
            if (Schema::hasColumn('module_practices', 'passing_grade')) {
                $table->dropColumn('passing_grade');
            }
        });

        Schema::table('free_tests', function (Blueprint $table) {
            if (Schema::hasColumn('free_tests', 'passing_grade')) {
                $table->dropColumn('passing_grade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Note: Down migration restores column passing_grade as integer nullable.
     * Approximate compatibility values are re-calculated from passing_score / total_score.
     */
    public function down(): void
    {
        Schema::table('final_exams', function (Blueprint $table) {
            if (! Schema::hasColumn('final_exams', 'passing_grade')) {
                $table->integer('passing_grade')->nullable()->after('total_score');
            }
        });

        Schema::table('module_practices', function (Blueprint $table) {
            if (! Schema::hasColumn('module_practices', 'passing_grade')) {
                $table->integer('passing_grade')->nullable()->after('total_score');
            }
        });

        Schema::table('free_tests', function (Blueprint $table) {
            if (! Schema::hasColumn('free_tests', 'passing_grade')) {
                $table->integer('passing_grade')->nullable()->after('total_score');
            }
        });

        // Repopulate approximate passing_grade for pass_fail records
        DB::statement("UPDATE final_exams SET passing_grade = ROUND((passing_score / total_score) * 100) WHERE result_mode = 'pass_fail' AND total_score > 0 AND passing_score IS NOT NULL");
        DB::statement("UPDATE module_practices SET passing_grade = ROUND((passing_score / total_score) * 100) WHERE result_mode = 'pass_fail' AND total_score > 0 AND passing_score IS NOT NULL");
        DB::statement("UPDATE free_tests SET passing_grade = ROUND((passing_score / total_score) * 100) WHERE result_mode = 'pass_fail' AND total_score > 0 AND passing_score IS NOT NULL");
    }
};
