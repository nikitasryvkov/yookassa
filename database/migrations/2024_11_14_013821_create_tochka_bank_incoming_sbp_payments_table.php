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
        Schema::create('tochka_bank_incoming_sbp_payments', function (Blueprint $table) {
            $table->id();
            $table->string('operation_id');
            $table->string('qrc_id');
            $table->decimal('amount');
            $table->string('payer_mobile_number');
            $table->string('payer_name');
            $table->string('brand_name');
            $table->string('merchant_id');
            $table->text('purpose');
            $table->unsignedBigInteger('customer_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tochka_bank_incoming_sbp_payments');
    }
};
