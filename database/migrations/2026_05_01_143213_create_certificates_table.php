<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_level_id');
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('final_exam_attempt_id');
            $table->unsignedBigInteger('certificate_template_id');

            $table->string('certificate_number', 100)->unique();
            $table->string('certificate_file')->nullable();

            $table->dateTime('issued_at')->nullable();

            $table->enum('status', [
                'issued',
                'revoked',
            ])->default('issued');

            $table->timestamps();

            $table->foreign('student_id', 'cert_student_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('course_level_id', 'cert_course_level_fk')
                ->references('id')
                ->on('course_levels')
                ->cascadeOnDelete();

            $table->foreign('enrollment_id', 'cert_enrollment_fk')
                ->references('id')
                ->on('student_course_enrollments')
                ->cascadeOnDelete();

            $table->foreign('final_exam_attempt_id', 'cert_exam_attempt_fk')
                ->references('id')
                ->on('final_exam_attempts')
                ->cascadeOnDelete();

            $table->foreign('certificate_template_id', 'cert_template_fk')
                ->references('id')
                ->on('certificate_templates')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
