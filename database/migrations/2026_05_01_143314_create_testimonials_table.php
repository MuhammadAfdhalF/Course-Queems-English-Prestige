<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('course_level_id')->nullable();

            $table->string('name');
            $table->string('photo')->nullable();

            $table->tinyInteger('rating')->nullable();
            $table->text('testimonial');

            $table->enum('type', [
                'company',
                'course',
            ])->default('company');

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(false);

            $table->timestamps();

            $table->foreign('student_id', 'testi_student_fk')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('course_level_id', 'testi_course_level_fk')
                ->references('id')
                ->on('course_levels')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
