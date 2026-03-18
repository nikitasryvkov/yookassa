<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('url_payments', function (Blueprint $table) {
            // Idempotence-Key, использованный при создании платежа/возврата в ЮKassa
            $table->string('idempotence_key', 64)->nullable()->after('system_status');

            // Технические поля ЮKassa
            $table->string('refund_id')->nullable()->after('system_id');
            $table->dateTime('refunded_at')->nullable()->after('payed');

            // Снимок ответа провайдера для диагностики (без хранения секретов)
            $table->json('provider_payload')->nullable()->after('refunded_at');
        });
    }

    public function down(): void
    {
        Schema::table('url_payments', function (Blueprint $table) {
            $table->dropColumn(['provider_payload', 'refunded_at', 'refund_id', 'idempotence_key']);
        });
    }
};

