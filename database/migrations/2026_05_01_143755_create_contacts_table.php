<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            $table->string('whatsapp_label', 50)->nullable();
            $table->string('whatsapp_link')->nullable();

            $table->string('email_label')->nullable();
            $table->string('email_link')->nullable();

            $table->string('operational_hours')->nullable();
            $table->text('address')->nullable();
            $table->text('map_embed_url')->nullable();

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
