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
        Schema::create('receipt_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Receipt::class, 'receipt_id');
            $table->string('status')->index();
            $table->string('fn_state');
            $table->json('failure_info')->nullable();
            $table->text('message');
            $table->dateTime('time_status_changed');
            $table->unsignedInteger('shift_number')->nullable();
            $table->unsignedInteger('check_number')->nullable();

            $table->string('kkt_number')->nullable();
            $table->string('fn_number')->nullable();
            $table->unsignedInteger('fn_doc_number')->nullable();
            $table->unsignedInteger('fn_doc_mark')->nullable();
            $table->dateTime('date')->nullable();
            $table->decimal('sum')->nullable();
            $table->string('check_type')->nullable();
            $table->string('qr')->nullable();
            $table->string('ecr_registration_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_statuses');
    }
};
