<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('information_post_images', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('information_post_id');

            $table->string('image');
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->foreign('information_post_id', 'ipi_post_fk')
                ->references('id')
                ->on('information_posts')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('information_post_images');
    }
};
