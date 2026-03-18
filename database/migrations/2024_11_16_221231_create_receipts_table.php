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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('bank_payment_type')->index();
            $table->unsignedBigInteger('payment_id')->index();

            $table->dateTime('checkout_date_time')->index();
            $table->string('doc_num')->index();
            //Тип документа
            $table->enum('doc_type', ['SALE', 'RETURN', 'BUY', 'BUY_RETURN', 'SALE_CORRECTION', 'SALE_RETURN_CORRECTION'])
            ->index();
            $table->string('email');
            $table->string('payment_type');
            $table->decimal('sum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
