<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('singapay_test_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('bill_no')->unique();
            $table->decimal('amount', 15, 2);
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20);
            $table->string('bill_description')->default('Test Transaction');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->string('singapay_ref')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('qr_data')->nullable();
            $table->string('payment_url')->nullable();
            $table->json('webhook_payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('singapay_test_transactions');
    }
};
