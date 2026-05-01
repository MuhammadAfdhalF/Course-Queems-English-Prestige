<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_social_links', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('contact_id');

            $table->enum('platform', [
                'instagram',
                'tiktok',
                'facebook',
                'youtube',
                'linkedin',
                'other',
            ]);

            $table->string('label', 100)->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->nullable();

            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('contact_id', 'csl_contact_fk')
                ->references('id')
                ->on('contacts')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_social_links');
    }
};