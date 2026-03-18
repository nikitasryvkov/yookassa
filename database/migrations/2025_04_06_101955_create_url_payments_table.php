<?php

use App\Models\User;
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
        Schema::create('url_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(UserType::class);
            $table->string('url');
            $table->string('system_id');
            $table->string('system_status');
            $table->decimal('sum');
            $table->decimal('total');
            $table->decimal('commission');
            $table->unsignedBigInteger('payment_point_id');
            $table->unsignedSmallInteger('payment_method_id')->index();
            $table->dateTime('payed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_payments');
    }
};
