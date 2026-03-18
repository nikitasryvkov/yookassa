<?php

use App\Models\Telegram\PaymentBotCommission;
use App\Models\UserType;
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
        Schema::create('bot_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->bigInteger('telegram_id')->index();
            $table->foreignIdFor(UserType::class);
            $table->decimal('sum');
            $table->decimal('total');
            $table->decimal('commission');
            $table->unsignedBigInteger('payment_bot_commission_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_payments');
    }
};
