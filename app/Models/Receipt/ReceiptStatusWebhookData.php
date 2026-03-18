<?php

namespace App\Models\Receipt;

use Illuminate\Database\Eloquent\Model;

class ReceiptStatusWebhookData extends Model
{
    protected $fillable = [
        'receipt_id',
        'response',
    ];
}
