<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('free_test_questions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('free_test_id');

            $table->enum('question_type', [
                'multiple_choice',
                'essay',
                'short_answer',
            ])->default('multiple_choice');

            $table->text('question');

            $table->string('option_a')->nullable();
            $table->string('option_b')->nullable();
            $table->string('option_c')->nullable();
            $table->string('option_d')->nullable();

            $table->string('correct_answer')->nullable();

            $table->integer('score')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('free_test_id', 'ftq_test_fk')
                ->references('id')
                ->on('free_tests')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('free_test_questions');
    }
};
