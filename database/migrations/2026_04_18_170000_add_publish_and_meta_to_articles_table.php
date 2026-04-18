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
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('slug');
            $table->timestamp('published_at')->nullable()->after('is_published');
            $table->string('meta_title', 255)->nullable()->after('image');
            $table->string('meta_description', 320)->nullable()->after('meta_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['is_published', 'published_at', 'meta_title', 'meta_description']);
        });
    }
};
