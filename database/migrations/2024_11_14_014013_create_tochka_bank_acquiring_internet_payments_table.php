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
        Schema::create('tochka_bank_acquiring_internet_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_code');
            $table->decimal('amount');
            $table->string('payment_type');
            $table->string('operation_id');
            $table->string('transaction_id');
            $table->text('purpose');
            $table->string('qrc_id');
            $table->string('payer_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tochka_bank_acquiring_internet_payments');
    }
};
