<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlPayment extends Model
{
    protected $fillable = [
        'user_id',
        'user_type_id',
        'url',
        'system_id',
        'refund_id',
        'yandex_token',
        'system_status',
        'idempotence_key',
        'sum',
        'total',
        'commission',
        'payment_point_id',
        'payment_method_id',
        'payed',
        'refunded_at',
        'provider_payload',
        'agent_commission_sum',
        'agent_commission_rate',
    ];

    protected function casts(): array
    {
        return [
            'payed' => 'datetime',
            'refunded_at' => 'datetime',
            'provider_payload' => 'array',
        ];
    }
}
