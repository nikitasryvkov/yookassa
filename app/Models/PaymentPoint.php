<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPoint extends Model
{
    protected $fillable = [
        'name',
        'payment_purpose',
        'merchant_id',
        'account_id',
        'customer_code',
        'yandex_token',
    ];
}
