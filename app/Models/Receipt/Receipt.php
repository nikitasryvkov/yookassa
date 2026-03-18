<?php

namespace App\Models\Receipt;

use App\Models\TochkaBank\TochkaBankAcquiringInternetPayment;
use App\Models\TochkaBank\TochkaBankIncomingSbpPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receipt extends Model
{
    protected $fillable = [
        'checkout_date_time',
        'doc_num',
        'doc_type',
        'email',
        'payment_type',
        'sum',
        'bank_payment_type',
        'payment_id',
    ];

    public function tbIncomingSbpPayment(): BelongsTo
    {
        return $this->belongsTo(TochkaBankIncomingSbpPayment::class);
    }

    public function tbAcquiringInternetPayment(): BelongsTo
    {
        return $this->belongsTo(TochkaBankAcquiringInternetPayment::class);
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(ReceiptStatus::class);
    }
}
