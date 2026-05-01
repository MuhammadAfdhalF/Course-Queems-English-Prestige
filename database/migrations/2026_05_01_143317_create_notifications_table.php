<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->string('title');
            $table->text('message');

            $table->enum('type', [
                'order',
                'payment',
                'practice',
                'exam',
                'certificate',
                'testimonial',
                'system',
            ])->default('system');

            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type', 100)->nullable();

            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();

            $table->timestamps();

            $table->foreign('user_id', 'notif_user_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
