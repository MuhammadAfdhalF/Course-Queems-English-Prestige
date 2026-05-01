<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_exam_attempts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('final_exam_id');

            $table->integer('attempt_number')->default(1);
            $table->decimal('total_score', 5, 2)->default(0);

            $table->enum('status', [
                'in_progress',
                'submitted',
                'passed',
                'failed',
                'waiting_review',
            ])->default('in_progress');

            $table->dateTime('started_at')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('graded_at')->nullable();

            $table->timestamps();

            $table->foreign('student_id', 'fea_student_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('final_exam_id', 'fea_exam_fk')
                ->references('id')
                ->on('final_exams')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_exam_attempts');
    }
};