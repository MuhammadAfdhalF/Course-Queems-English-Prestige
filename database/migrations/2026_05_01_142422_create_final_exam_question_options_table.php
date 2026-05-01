<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_exam_question_options', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('final_exam_question_id');

            $table->string('option_label', 10);
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->foreign('final_exam_question_id', 'feqo_question_fk')
                ->references('id')
                ->on('final_exam_questions')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_exam_question_options');
    }
};