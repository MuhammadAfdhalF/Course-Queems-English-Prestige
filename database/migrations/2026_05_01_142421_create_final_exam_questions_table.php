<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_exam_questions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('final_exam_id');

            $table->enum('question_type', [
                'multiple_choice',
                'short_answer',
                'essay',
                'upload',
            ]);

            $table->longText('question');
            $table->text('explanation')->nullable();
            $table->decimal('score', 5, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('final_exam_id', 'feq_exam_fk')
                ->references('id')
                ->on('final_exams')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_exam_questions');
    }
};