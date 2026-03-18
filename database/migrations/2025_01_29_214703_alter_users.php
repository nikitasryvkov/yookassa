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
            $table->decimal('qr_commission_rate')->after('payment_point_id')->default(0);
            $table->decimal('card_commission_rate')->after('payment_point_id')->default(0);
            $table->decimal('yandex_commission_rate')->after('payment_point_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['qr_commission_rate', 'card_commission_rate', 'yandex_commission_rate']);
        });
    }
};
