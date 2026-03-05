<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('disbursements')) Schema::create('disbursements', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('status');
            $table->string('bank_code');
            $table->string('bank_account_number');
            $table->string('bank_account_name')->nullable();
            $table->string('post_timestamp');
            $table->string('processed_timestamp')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('amount_value', 15, 2)->default(0);
            $table->string('amount_currency')->default('IDR');
            $table->decimal('total_amount_value', 15, 2)->default(0);
            $table->string('total_amount_currency')->default('IDR');
            $table->json('fees')->nullable();
            $table->string('source_account_id')->nullable();
            $table->json('balance_after')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('disbursements');
    }
};
