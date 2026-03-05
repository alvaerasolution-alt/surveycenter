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
        Schema::create('customer_stories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('highlight'); // contoh: "11.8 billion"
            $table->string('highlight_color')->default('#000000'); // warna teks highlight
            $table->string('subheading'); // contoh: "Data Points Consolidated"
            $table->text('description');
            $table->string('image'); // path gambar
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_stories');
    }
};
