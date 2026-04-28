<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referral_commissions', function (Blueprint $table) {
            $table->unsignedBigInteger('commission_amount')->default(0)->after('transaction_id')
                  ->comment('Commission in Rupiah');
            $table->decimal('commission_percent', 5, 2)->default(10)->after('commission_amount')
                  ->comment('Percent applied at the time of award');

            // Make point_transaction_id nullable (no longer required)
            $table->foreignId('point_transaction_id')->nullable()->change();
            // Make points_earned nullable (kept for backward compat)
            $table->integer('points_earned')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('referral_commissions', function (Blueprint $table) {
            $table->dropColumn(['commission_amount', 'commission_percent']);
            $table->foreignId('point_transaction_id')->nullable(false)->change();
            $table->integer('points_earned')->nullable(false)->change();
        });
    }
};
