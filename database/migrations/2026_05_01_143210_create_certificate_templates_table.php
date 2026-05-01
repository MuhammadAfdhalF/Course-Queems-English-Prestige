<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('course_program_id')->nullable();

            $table->string('name');
            $table->string('background_image')->nullable();

            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('course_program_id', 'ct_program_fk')
                ->references('id')
                ->on('course_programs')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};