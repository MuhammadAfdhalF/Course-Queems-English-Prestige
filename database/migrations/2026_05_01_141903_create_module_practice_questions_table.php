<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_practice_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('module_practice_id')
                ->constrained('module_practices')
                ->cascadeOnDelete();

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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_practice_questions');
    }
};
