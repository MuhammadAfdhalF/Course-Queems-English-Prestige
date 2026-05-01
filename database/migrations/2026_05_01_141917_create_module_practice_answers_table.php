<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_practice_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('module_practice_attempt_id')
                ->constrained('module_practice_attempts')
                ->cascadeOnDelete();

            $table->foreignId('module_practice_question_id')
                ->constrained('module_practice_questions')
                ->cascadeOnDelete();

            $table->foreignId('selected_option_id')
                ->nullable()
                ->constrained('module_practice_question_options')
                ->nullOnDelete();

            $table->longText('answer_text')->nullable();
            $table->string('uploaded_file')->nullable();

            $table->decimal('score', 5, 2)->default(0);
            $table->text('feedback')->nullable();
            $table->boolean('is_correct')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_practice_answers');
    }
};
