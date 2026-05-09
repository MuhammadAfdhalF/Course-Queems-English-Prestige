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
        Schema::table('course_levels', function (Blueprint $table) {
            $table->enum('learning_mode', ['online', 'offline', 'hybrid'])
                ->default('online')
                ->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_levels', function (Blueprint $table) {
            $table->dropColumn('learning_mode');
        });
    }
};
