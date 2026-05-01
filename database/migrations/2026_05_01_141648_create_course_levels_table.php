<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_levels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_program_id')
                ->constrained('course_programs')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique();

            $table->enum('thumbnail_type', ['image', 'video'])->nullable();
            $table->string('thumbnail_file')->nullable();

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->decimal('price', 12, 2)->default(0);

            $table->enum('access_type', ['lifetime', 'limited'])->default('lifetime');
            $table->integer('access_duration_days')->nullable();

            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_levels');
    }
};