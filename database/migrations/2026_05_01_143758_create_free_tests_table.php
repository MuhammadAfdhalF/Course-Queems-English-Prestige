<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('free_tests', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('category', 100)->nullable();
            $table->text('description')->nullable();

            $table->integer('duration_minutes')->nullable();
            $table->integer('total_questions')->default(0);
            $table->integer('passing_grade')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('free_tests');
    }
};
