<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_course_enrollments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_level_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->enum('enrollment_source', [
                'order',
                'manual',
                'promo',
                'free',
            ])->default('order');

            $table->enum('status', [
                'active',
                'completed',
                'expired',
                'cancelled',
            ])->default('active');

            $table->decimal('progress_percentage', 5, 2)->default(0);

            $table->dateTime('enrolled_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('expired_at')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();

            $table->foreign('student_id', 'sce_student_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('course_level_id', 'sce_course_level_fk')
                ->references('id')
                ->on('course_levels')
                ->cascadeOnDelete();

            $table->foreign('order_id', 'sce_order_fk')
                ->references('id')
                ->on('orders')
                ->nullOnDelete();

            $table->foreign('created_by', 'sce_admin_fk')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_course_enrollments');
    }
};