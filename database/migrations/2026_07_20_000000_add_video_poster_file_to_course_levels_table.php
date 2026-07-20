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
            $table->string('video_poster_file')->nullable()->after('thumbnail_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_levels', function (Blueprint $table) {
            $table->dropColumn('video_poster_file');
        });
    }
};
