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
        Schema::table('users', function (Blueprint $table) {
            $table->string('bic')->after('payment_point_id')->nullable();
            $table->string('payment_purpose')->after('payment_point_id')->nullable();
            $table->string('counterparty_name')->after('payment_point_id')->nullable();
            $table->string('account_number')->after('payment_point_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bic', 'payment_purpose', 'counterparty_name', 'account_number']);
        });
    }
};
