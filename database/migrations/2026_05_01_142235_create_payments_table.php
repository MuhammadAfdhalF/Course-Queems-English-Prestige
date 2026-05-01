<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_level_id');
            $table->unsignedBigInteger('confirmed_by')->nullable();

            $table->decimal('amount', 12, 2)->default(0);

            $table->enum('payment_method', [
                'manual_transfer',
                'cash',
                'other',
            ])->default('manual_transfer');

            $table->enum('payment_status', [
                'unpaid',
                'paid',
                'cancelled',
            ])->default('unpaid');

            $table->string('proof_file')->nullable();
            $table->dateTime('payment_date')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();

            $table->foreign('order_id', 'payments_order_fk')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();

            $table->foreign('student_id', 'payments_student_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('course_level_id', 'payments_course_level_fk')
                ->references('id')
                ->on('course_levels')
                ->cascadeOnDelete();

            $table->foreign('confirmed_by', 'payments_admin_fk')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};