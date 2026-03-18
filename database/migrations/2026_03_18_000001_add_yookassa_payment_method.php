<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Не полагаемся на автонумерацию: в проекте уже есть жесткие id (1..3).
        DB::table('payment_methods')->updateOrInsert(
            ['id' => 4],
            ['name' => 'YooKassa']
        );
    }

    public function down(): void
    {
        DB::table('payment_methods')->where('id', 4)->delete();
    }
};

