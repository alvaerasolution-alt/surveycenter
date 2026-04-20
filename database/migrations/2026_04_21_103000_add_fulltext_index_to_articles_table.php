<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if (!in_array($driver, ['mysql', 'mariadb', 'pgsql'], true)) {
            return;
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->fullText(['title', 'excerpt', 'content'], 'articles_search_fulltext');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if (!in_array($driver, ['mysql', 'mariadb', 'pgsql'], true)) {
            return;
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->dropFullText('articles_search_fulltext');
        });
    }
};
