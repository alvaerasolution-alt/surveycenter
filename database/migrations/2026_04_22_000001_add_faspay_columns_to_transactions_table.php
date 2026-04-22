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
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'bill_no')) {
                $table->string('bill_no')->nullable()->after('singapay_ref');
            }
            if (!Schema::hasColumn('transactions', 'payment_ref')) {
                $table->string('payment_ref')->nullable()->after('bill_no');
            }
            if (!Schema::hasColumn('transactions', 'trx_id')) {
                $table->string('trx_id')->nullable()->after('payment_ref');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['bill_no', 'payment_ref', 'trx_id']);
        });
    }
};
