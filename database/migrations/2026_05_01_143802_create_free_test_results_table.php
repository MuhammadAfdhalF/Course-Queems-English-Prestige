<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('free_test_results', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('free_test_id');

            $table->string('participant_name')->nullable();
            $table->string('participant_email')->nullable();
            $table->string('participant_whatsapp', 30)->nullable();

            $table->integer('total_score')->default(0);
            $table->text('recommendation')->nullable();

            $table->dateTime('submitted_at')->nullable();

            $table->timestamps();

            $table->foreign('free_test_id', 'ftr_test_fk')
                ->references('id')
                ->on('free_tests')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('free_test_results');
    }
};
