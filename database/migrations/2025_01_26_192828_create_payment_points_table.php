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
        Schema::create('payment_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('payment_purpose');
            $table->string('merchant_id');
            $table->string('account_id');
            $table->string('customer_code');
            $table->string('yandex_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_points');
    }
};
