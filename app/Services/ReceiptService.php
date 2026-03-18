<?php

namespace App\Services;

use App\DTO\Receipt\InventPositionDTO;
use App\DTO\Receipt\ReceiptDTO;
use App\Enums\TochkaBankPaymentTypes;
use App\Enums\VatTags;
use App\Models\Receipt\Receipt;
use App\Models\Receipt\ReceiptInventPosition;
use App\Models\TochkaBank\TochkaBankAcquiringInternetPayment;
use App\Models\TochkaBank\TochkaBankIncomingSbpPayment;

class ReceiptService
{
    private ?ReceiptDTO $receiptDTO;

    public function __construct(int $type, int $id, string $docType)
    {
        $payment = match ($type) {
            TochkaBankPaymentTypes::IncomingSbpPayment->value =>
            TochkaBankIncomingSbpPayment::query()->where('id', $id)->first(),
            TochkaBankPaymentTypes::AcquiringInternetPayment->value =>
            TochkaBankAcquiringInternetPayment::query()->where('id', $id)->first(),
        };

        $this->receiptDTO = $this->initReceipt($payment, $docType);
    }

    public function getReceiptDTO(): array
    {
        if(is_null($this->receiptDTO)) {
            return [];
        }

        return $this->receiptDTO->getArray();

    }

    private function initReceipt(
        null|TochkaBankIncomingSbpPayment|TochkaBankAcquiringInternetPayment $payment,
        string $docType
    ): ?ReceiptDTO
    {

        if (is_null($payment))
        {
            return null;
        }

        $receipt = Receipt::query()
            ->where('payment_id', $payment->id)
            ->where('bank_payment_type', $payment->type)
            ->first();

        if(!is_null($receipt))
        {
            return null;
        }

        //инициализируем объект чека
        $receiptArray = [];
        $receiptArray['checkout_date_time'] = now()->format('Y-m-d H:i:s');
        $receiptArray['doc_num'] = $payment->type . '-' . $payment->id;
        $receiptArray['doc_type']  = $docType;
        $receiptArray['email'] = $payment->payer_mobile_number ?? 'D.SHALIAKIN@yandex.ru';
        $receiptArray['payment_type'] = 'CARD';
        $receiptArray['sum'] = $payment->amount ?? $payment->side_payer_amount;
        $receiptArray['bank_payment_type'] = $payment->type;
        $receiptArray['payment_id'] = $payment->id;

        $receiptArray['id'] = Receipt::query()->create($receiptArray)->id;
        $receiptDTO = new ReceiptDTO($receiptArray);


        $inventPositionArray = [];
        $inventPositionArray['receipt_id'] = $receiptArray['id'];
        $inventPositionArray['name'] = $payment->purpose . '. Заказ № ' . time();
        $inventPositionArray['price'] = $payment->amount ?? $payment->side_payer_amount;
        $inventPositionArray['qty'] = 1;
        $inventPositionArray['measure'] = 'pcs';
        $inventPositionArray['vat_tag'] = VatTags::NotUsed->value;
        $inventPositionArray['payment_object'] = 'service';

        ReceiptInventPosition::query()->create($inventPositionArray);

        return $receiptDTO->addInventPosition(new InventPositionDTO($inventPositionArray));
    }

}
