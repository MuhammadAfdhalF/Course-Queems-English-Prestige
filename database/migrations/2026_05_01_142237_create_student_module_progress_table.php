<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_module_progress', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('module_id');

            $table->enum('status', [
                'locked',
                'open',
                'in_progress',
                'completed',
            ])->default('locked');

            $table->decimal('progress_percentage', 5, 2)->default(0);

            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->timestamps();

            $table->foreign('enrollment_id', 'smp_enrollment_fk')
                ->references('id')
                ->on('student_course_enrollments')
                ->cascadeOnDelete();

            $table->foreign('module_id', 'smp_module_fk')
                ->references('id')
                ->on('modules')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_module_progress');
    }
};