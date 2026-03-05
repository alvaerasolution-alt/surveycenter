<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discount_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');            // Judul promo
            $table->string('subtitle')->nullable(); // Deskripsi pendek
            $table->string('button_text')->nullable(); 
            $table->string('button_link')->nullable(); 
            $table->string('image')->nullable(); // Gambar promo
            $table->string('background')->nullable(); // Gradient/background
            $table->integer('order')->default(0); // Urutan slide
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_banners');
    }
};
