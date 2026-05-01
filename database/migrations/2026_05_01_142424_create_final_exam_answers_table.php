<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_exam_answers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('final_exam_attempt_id');
            $table->unsignedBigInteger('final_exam_question_id');
            $table->unsignedBigInteger('selected_option_id')->nullable();

            $table->longText('answer_text')->nullable();
            $table->string('uploaded_file')->nullable();

            $table->decimal('score', 5, 2)->default(0);
            $table->text('feedback')->nullable();
            $table->boolean('is_correct')->nullable();

            $table->timestamps();

            $table->foreign('final_exam_attempt_id', 'fea_answer_attempt_fk')
                ->references('id')
                ->on('final_exam_attempts')
                ->cascadeOnDelete();

            $table->foreign('final_exam_question_id', 'fea_answer_question_fk')
                ->references('id')
                ->on('final_exam_questions')
                ->cascadeOnDelete();

            $table->foreign('selected_option_id', 'fea_answer_option_fk')
                ->references('id')
                ->on('final_exam_question_options')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_exam_answers');
    }
};