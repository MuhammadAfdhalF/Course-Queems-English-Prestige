<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_level_id');

            $table->string('order_code', 100)->unique();
            $table->decimal('price', 12, 2)->default(0);

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'cancelled',
            ])->default('pending');

            $table->dateTime('order_date')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();

            $table->foreign('student_id', 'orders_student_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('course_level_id', 'orders_course_level_fk')
                ->references('id')
                ->on('course_levels')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};