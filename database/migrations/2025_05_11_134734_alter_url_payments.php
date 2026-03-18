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
        Schema::table('url_payments', function (Blueprint $table) {
            $table->decimal('agent_commission_sum')->after('commission')->default(0);
            $table->decimal('agent_commission_rate')->after('commission')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('url_payments', function (Blueprint $table) {
            $table->dropColumn(['agent_commission_rate', 'agent_commission_sum']);
        });
    }
};
