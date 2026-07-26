<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_levels', function (Blueprint $table) {
            $table->string('certificate_score_label', 100)->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('course_levels', function (Blueprint $table) {
            $table->dropColumn('certificate_score_label');
        });
    }
};
