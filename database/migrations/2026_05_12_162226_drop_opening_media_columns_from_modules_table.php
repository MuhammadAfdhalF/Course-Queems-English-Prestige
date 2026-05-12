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
        Schema::table('modules', function (Blueprint $table) {
            if (Schema::hasColumn('modules', 'opening_media_type')) {
                $table->dropColumn('opening_media_type');
            }

            if (Schema::hasColumn('modules', 'opening_media_file')) {
                $table->dropColumn('opening_media_file');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            if (! Schema::hasColumn('modules', 'opening_media_type')) {
                $table->enum('opening_media_type', ['image', 'video'])->nullable()->after('short_description');
            }

            if (! Schema::hasColumn('modules', 'opening_media_file')) {
                $table->string('opening_media_file')->nullable()->after('opening_media_type');
            }
        });
    }
};
