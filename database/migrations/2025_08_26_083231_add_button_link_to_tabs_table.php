<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tabs', function (Blueprint $table) {
            $table->string('button_link')->nullable()->after('button_text');
        });
    }

    public function down(): void
    {
        Schema::table('tabs', function (Blueprint $table) {
            $table->dropColumn('button_link');
        });
    }
};
