<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE certificates MODIFY status ENUM('locked', 'issued', 'revoked') NOT NULL DEFAULT 'locked'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('certificates')
            ->where('status', 'locked')
            ->update([
                'status' => 'issued',
            ]);

        DB::statement("ALTER TABLE certificates MODIFY status ENUM('issued', 'revoked') NOT NULL DEFAULT 'issued'");
    }
};