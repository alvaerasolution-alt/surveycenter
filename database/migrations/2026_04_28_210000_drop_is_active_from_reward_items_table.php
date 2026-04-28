<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reward_items', function (Blueprint $table) {
            $table->dropIndex(['category', 'is_active']);
            $table->dropColumn('is_active');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::table('reward_items', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->boolean('is_active')->default(true);
            $table->index(['category', 'is_active']);
        });
    }
};
