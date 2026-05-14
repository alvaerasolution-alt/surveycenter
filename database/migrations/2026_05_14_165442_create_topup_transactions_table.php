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
        Schema::create('topup_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending'); // pending, processing, paid, failed
            $table->string('payment_method')->nullable();
            
            // SingaPay / Faspay references
            $table->string('singapay_ref')->nullable();
            $table->string('bill_no')->nullable();
            $table->string('payment_ref')->nullable();
            $table->string('trx_id')->nullable();
            $table->text('qr_data')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topup_transactions');
    }
};
