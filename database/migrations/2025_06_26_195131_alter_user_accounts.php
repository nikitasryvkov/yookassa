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
        Schema::table('user_accounts', function (Blueprint $table) {
            $table->string('bic')->after('details')->nullable();
            $table->string('payment_purpose')->after('details')->nullable();
            $table->string('counterparty_name')->after('details')->nullable();
            $table->string('account_number')->after('details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_accounts', function (Blueprint $table) {
            $table->dropColumn(['bic', 'payment_purpose', 'counterparty_name', 'account_number']);
        });
    }
};
