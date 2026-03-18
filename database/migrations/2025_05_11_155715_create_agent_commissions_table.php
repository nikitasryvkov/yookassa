<?php

use App\Models\PaymentMethod;
use App\Models\PaymentPoint;
use App\Models\User;
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
        Schema::create('agent_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->index();
            $table->unsignedTinyInteger('source')->index();
            $table->decimal('sum');
            $table->decimal('total');
            $table->decimal('agent_commission_sum');
            $table->decimal('agent_commission_rate');
            $table->foreignIdFor(PaymentPoint::class)->index();
            $table->foreignIdFor(PaymentMethod::class)->index();
            $table->dateTime('payed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_commissions');
    }
};
