<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentCommission extends Model
{
    protected $fillable = [
        'user_id',
        'source',
        'sum',
        'total',
        'agent_commission_sum',
        'agent_commission_rate',
        'payment_point_id',
        'payment_method_id',
        'status',
        'request_id',
        'request_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paymentPoint(): BelongsTo
    {
        return $this->belongsTo(PaymentPoint::class, 'payment_point_id');
    }
}
