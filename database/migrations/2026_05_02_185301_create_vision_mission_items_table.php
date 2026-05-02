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
        Schema::create('vision_mission_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visions_mission_id')
                ->constrained('visions_missions')
                ->cascadeOnDelete();
            $table->enum('type', ['mission'])->default('mission');
            $table->text('content');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vision_mission_items');
    }
};
