<?php

use App\Models\Receipt\Receipt;
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
        Schema::create('receipt_invent_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Receipt::class, 'receipt_id');
            $table->string('name');
            $table->decimal('price');
            $table->decimal('qty');
            $table->string('measure');
            $table->unsignedInteger('vat_tag');
            $table->string('payment_object');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_invent_positions');
    }
};
