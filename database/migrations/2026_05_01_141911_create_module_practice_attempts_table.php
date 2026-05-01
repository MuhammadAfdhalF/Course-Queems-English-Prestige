<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_practice_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('module_practice_id')
                ->constrained('module_practices')
                ->cascadeOnDelete();

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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_practice_attempts');
    }
};
