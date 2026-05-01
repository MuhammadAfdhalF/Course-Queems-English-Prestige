<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_exams', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('course_level_id');

            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('passing_grade')->default(0);

            $table->enum('grading_method', [
                'auto',
                'manual',
                'mixed',
            ])->default('auto');

            $table->integer('max_attempts')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('course_level_id', 'fe_course_level_fk')
                ->references('id')
                ->on('course_levels')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_exams');
    }
};