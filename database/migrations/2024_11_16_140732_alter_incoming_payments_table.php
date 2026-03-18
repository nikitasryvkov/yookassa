<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tochka_bank_incoming_payments', function (Blueprint $table) {
            $table->string('payment_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tochka_bank_incoming_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_id')->change();
        });
    }
};
