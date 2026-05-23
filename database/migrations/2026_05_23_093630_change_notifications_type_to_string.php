<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE notifications MODIFY type VARCHAR(100) NOT NULL DEFAULT 'system'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE notifications MODIFY type ENUM('order', 'payment', 'system') NOT NULL DEFAULT 'system'");
    }
};
