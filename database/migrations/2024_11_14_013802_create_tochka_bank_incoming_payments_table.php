<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tochka_bank_incoming_payments', function (Blueprint $table) {
            $table->id();
            $table->string('side_payer_bank_code');
            $table->string('side_payer_bank_name');
            $table->string('side_payer_bank_correspondent_account');
            $table->string('side_payer_account');
            $table->string('side_payer_name');
            $table->decimal('side_payer_amount');
            $table->string('side_payer_currency');
            $table->unsignedBigInteger('side_payer_inn');
            $table->unsignedInteger('side_payer_kpp');

            $table->string('side_recipient_bank_code');
            $table->string('side_recipient_bank_name');
            $table->string('side_recipient_bank_correspondent_account');
            $table->string('side_recipient_account');
            $table->string('side_recipient_name');
            $table->decimal('side_recipient_amount');
            $table->string('side_recipient_currency');
            $table->unsignedBigInteger('side_recipient_inn');
            $table->unsignedInteger('side_recipient_kpp');

            $table->text('purpose');
            $table->unsignedInteger('document_number');
            $table->unsignedBigInteger('payment_id');
            $table->date('date');
            $table->unsignedInteger('customer_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tochka_bank_incoming_payments');
    }
};
