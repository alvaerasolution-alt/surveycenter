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
        Schema::create('faspay_test_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Transaction Details
            $table->string('bill_no')->unique();      // Order number for Faspay
            $table->string('bill_description')->nullable();
            $table->decimal('amount', 15, 2);         // Transaction amount
            $table->string('currency', 3)->default('IDR');
            
            // Customer Info
            $table->string('customer_name')->nullable()->default('Guest');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            
            // Payment Status
            $table->enum('status', ['unpaid', 'processing', 'paid', 'failed', 'expired', 'cancelled'])->default('unpaid');
            
            // Faspay References
            $table->string('trx_id')->nullable()->unique();           // Faspay transaction ID
            $table->string('payment_reff')->nullable();              // Payment reference from Faspay
            $table->string('payment_channel')->nullable();            // Payment method used
            $table->timestamp('payment_date')->nullable();            // When payment was completed
            
            // Payment Method Details
            $table->string('bank_user_name')->nullable();             // Bank/channel user name
            $table->text('payment_response')->nullable();             // Full webhook response stored
            
            // Testing Purposes
            $table->text('notes')->nullable();                        // Internal notes
            $table->json('metadata')->nullable();                     // Additional data
            
            // Timestamps
            $table->timestamps();
            $table->timestamp('expires_at')->nullable();
            
            // Indexes
            $table->index('status');
            $table->index('created_at');
            $table->index('bill_no');
            $table->index('trx_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faspay_test_transactions');
    }
};
