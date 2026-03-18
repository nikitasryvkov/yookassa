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
        Schema::table('bot_payments', function (Blueprint $table) {
            $table->unsignedSmallInteger('payment_method_id')->after('payment_point_id')->default(2)->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_payments', function (Blueprint $table) {
            $table->dropColumn('payment_method_id');
        });
    }
};
