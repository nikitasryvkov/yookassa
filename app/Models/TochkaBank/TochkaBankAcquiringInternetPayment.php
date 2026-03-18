<?php

namespace App\Models\TochkaBank;

use App\Enums\TochkaBankPaymentTypes;
use App\Models\Receipt\Receipt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TochkaBankAcquiringInternetPayment extends Model
{
    protected $fillable =[
        'customer_code',
        'amount',
        'payment_type',
        'operation_id',
        'transaction_id',
        'purpose',
        'qrc_id',
        'payer_name',
    ];

    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class, 'payment_id', 'id')
            ->where('bank_payment_type', TochkaBankPaymentTypes::AcquiringInternetPayment->value);
    }
}
