<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('final_exam_attempts', function (Blueprint $table) {
            $table->renameColumn('total_score', 'percentage_score');
        });

        Schema::table('final_exam_attempts', function (Blueprint $table) {
            $table->decimal('percentage_score', 5, 2)->nullable()->change();
            $table->decimal('raw_score', 8, 2)->nullable()->after('attempt_number');
            $table->decimal('max_score', 8, 2)->nullable()->after('raw_score');
            $table->string('result_mode', 20)->nullable()->after('percentage_score');
            $table->decimal('passing_score', 8, 2)->nullable()->after('result_mode');
            $table->boolean('is_passed')->nullable()->after('passing_score');
        });

        Schema::table('module_practice_attempts', function (Blueprint $table) {
            $table->renameColumn('total_score', 'percentage_score');
        });

        Schema::table('module_practice_attempts', function (Blueprint $table) {
            $table->decimal('percentage_score', 5, 2)->nullable()->change();
            $table->decimal('raw_score', 8, 2)->nullable()->after('attempt_number');
            $table->decimal('max_score', 8, 2)->nullable()->after('raw_score');
            $table->string('result_mode', 20)->nullable()->after('percentage_score');
            $table->decimal('passing_score', 8, 2)->nullable()->after('result_mode');
            $table->boolean('is_passed')->nullable()->after('passing_score');
        });

        Schema::table('free_test_results', function (Blueprint $table) {
            $table->decimal('raw_score', 8, 2)->nullable()->after('participant_whatsapp');
            $table->decimal('max_score', 8, 2)->nullable()->after('raw_score');
            $table->decimal('percentage_score', 5, 2)->nullable()->after('max_score');
            $table->string('result_mode', 20)->nullable()->after('percentage_score');
            $table->decimal('passing_score', 8, 2)->nullable()->after('result_mode');
            $table->boolean('is_passed')->nullable()->after('passing_score');
        });
    }

    public function down(): void
    {
        Schema::table('final_exam_attempts', function (Blueprint $table) {
            $table->dropColumn(['raw_score', 'max_score', 'result_mode', 'passing_score', 'is_passed']);
        });

        Schema::table('final_exam_attempts', function (Blueprint $table) {
            $table->renameColumn('percentage_score', 'total_score');
        });

        Schema::table('final_exam_attempts', function (Blueprint $table) {
            $table->decimal('total_score', 5, 2)->default(0.00)->nullable(false)->change();
        });

        Schema::table('module_practice_attempts', function (Blueprint $table) {
            $table->dropColumn(['raw_score', 'max_score', 'result_mode', 'passing_score', 'is_passed']);
        });

        Schema::table('module_practice_attempts', function (Blueprint $table) {
            $table->renameColumn('percentage_score', 'total_score');
        });

        Schema::table('module_practice_attempts', function (Blueprint $table) {
            $table->decimal('total_score', 5, 2)->default(0.00)->nullable(false)->change();
        });

        Schema::table('free_test_results', function (Blueprint $table) {
            $table->dropColumn(['raw_score', 'max_score', 'percentage_score', 'result_mode', 'passing_score', 'is_passed']);
        });
    }
};
