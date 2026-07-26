<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::table('final_exam_attempts')->whereNull('max_score')->update(['max_score' => 100.00]);
        \Illuminate\Support\Facades\DB::table('final_exam_attempts')->whereNull('result_mode')->update(['result_mode' => 'pass_fail']);

        \Illuminate\Support\Facades\DB::table('module_practice_attempts')->whereNull('max_score')->update(['max_score' => 100.00]);
        \Illuminate\Support\Facades\DB::table('module_practice_attempts')->whereNull('result_mode')->update(['result_mode' => 'pass_fail']);

        \Illuminate\Support\Facades\DB::table('free_test_results')->whereNull('max_score')->update(['max_score' => 100.00]);
        \Illuminate\Support\Facades\DB::table('free_test_results')->whereNull('result_mode')->update(['result_mode' => 'pass_fail']);

        Schema::table('final_exam_attempts', function (Blueprint $table) {
            $table->decimal('max_score', 8, 2)->nullable(false)->change();
            $table->string('result_mode')->nullable(false)->change();
        });

        Schema::table('module_practice_attempts', function (Blueprint $table) {
            $table->decimal('max_score', 8, 2)->nullable(false)->change();
            $table->string('result_mode')->nullable(false)->change();
        });

        Schema::table('free_test_results', function (Blueprint $table) {
            $table->decimal('max_score', 8, 2)->nullable(false)->change();
            $table->string('result_mode')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('final_exam_attempts', function (Blueprint $table) {
            $table->decimal('max_score', 8, 2)->nullable()->change();
            $table->string('result_mode')->nullable()->change();
        });

        Schema::table('module_practice_attempts', function (Blueprint $table) {
            $table->decimal('max_score', 8, 2)->nullable()->change();
            $table->string('result_mode')->nullable()->change();
        });

        Schema::table('free_test_results', function (Blueprint $table) {
            $table->decimal('max_score', 8, 2)->nullable()->change();
            $table->string('result_mode')->nullable()->change();
        });
    }
};
