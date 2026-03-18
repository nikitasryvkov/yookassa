<?php

namespace App\Models\Receipt;

use Illuminate\Database\Eloquent\Model;

class ReceiptInventPosition extends Model
{
    protected $fillable = [
        'receipt_id',
        'name',
        'price',
        'qty',
        'measure',
        'vat_tag',
        'payment_object',
    ];
}
