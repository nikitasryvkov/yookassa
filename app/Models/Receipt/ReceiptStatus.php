<?php

namespace App\Models\Receipt;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptStatus extends Model
{
    protected $fillable = [
        'receipt_id',
        'status',
        'fn_state',
        'failure_info',
        'message',
        'time_status_changed',
        'shift_number',
        'check_number',
        'kkt_number',
        'fn_number',
        'fn_doc_number',
        'fn_doc_mark',
        'date',
        'sum',
        'check_type',
        'qr',
        'ecr_registration_number',
    ];

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class);
    }
}
