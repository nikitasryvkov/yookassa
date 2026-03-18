<?php

namespace App\Models\TochkaBank;

use App\Enums\TochkaBankPaymentTypes;
use App\Models\Receipt\Receipt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TochkaBankIncomingSbpPayment extends Model
{
    protected $fillable = [
        'operation_id',
        'qrc_id',
        'amount',
        'payer_mobile_number',
        'payer_name',
        'brand_name',
        'merchant_id',
        'purpose',
        'customer_code',
    ];

    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class, 'payment_id', 'id')
            ->where('bank_payment_type', TochkaBankPaymentTypes::IncomingSbpPayment->value);
    }
}
