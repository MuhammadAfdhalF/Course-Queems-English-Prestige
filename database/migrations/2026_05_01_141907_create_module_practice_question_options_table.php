<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_practice_question_options', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('module_practice_question_id');

            $table->string('option_label', 10);
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->foreign('module_practice_question_id', 'mpqo_question_fk')
                ->references('id')
                ->on('module_practice_questions')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_practice_question_options');
    }
};