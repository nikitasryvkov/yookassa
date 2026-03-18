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
        Schema::table('agent_commissions', function (Blueprint $table) {
            $table->index(['agent_commission_rate']);
            $table->dropColumn('payed');
            $table->string('status')->nullable()->after('payment_method_id');
            $table->dateTime('request_date')->nullable()->after('payment_method_id');
            $table->string('request_id')->nullable()->after('payment_method_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_commissions', function (Blueprint $table) {
            $table->dropIndex(['agent_commission_rate']);
            $table->dropColumn(['status', 'request_id', 'request_date']);
            $table->dateTime('payed')->nullable();
        });
    }
};
