<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_bot_commissions', function (Blueprint $table) {
            $table->id();
            $table->decimal('commission');
            $table->string('service');
            $table->timestamps();
        });

        DB::table('payment_bot_commissions')->insert([
            [
                'commission' => 5,
                'service' => 'Card',
            ],
            [
                'commission' => 5,
                'service' => 'QR',
            ],
            [
                'commission' => 7,
                'service' => 'Yandex_split',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_bot_commissions');
    }
};
