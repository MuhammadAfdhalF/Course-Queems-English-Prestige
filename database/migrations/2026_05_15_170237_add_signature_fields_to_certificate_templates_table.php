<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->string('signature_image')->nullable()->after('background_image');
            $table->string('signer_name')->nullable()->after('signature_image');
            $table->string('signer_title')->nullable()->after('signer_name');
        });
    }

    public function down(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->dropColumn([
                'signature_image',
                'signer_name',
                'signer_title',
            ]);
        });
    }
};
