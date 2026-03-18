<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotPayments extends Model
{
    protected $fillable = [
        'id',
	    'user_id',
	    'telegram_id',
	    'user_type_id',
	    'total',
        'sum',
        'commission',
	    'payment_point_id',
        'payment_method_id',
        'payer_tag',
        'agent_commission_sum',
        'agent_commission_rate',
        'payment_id',
    ];
}
