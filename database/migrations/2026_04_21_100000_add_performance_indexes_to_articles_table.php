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
            $table->index(['is_published', 'created_at'], 'articles_published_created_at_index');
            $table->index(['is_published', 'category'], 'articles_published_category_index');
            $table->index(['is_published', 'updated_at'], 'articles_published_updated_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('articles_published_created_at_index');
            $table->dropIndex('articles_published_category_index');
            $table->dropIndex('articles_published_updated_at_index');
        });
    }
};
