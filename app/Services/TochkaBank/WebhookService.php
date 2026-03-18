<?php

namespace App\Services\TochkaBank;

use App\Enums\TochkaBankPaymentTypes;
use App\Models\TochkaBank\TochkaBankAcquiringInternetPayment;
use App\Models\TochkaBank\TochkaBankIncomingSbpPayment;
use App\Services\IssueReceiptService;
use stdClass;

class WebhookService
{

    public function __construct(
        private readonly IssueReceiptService $issueReceiptService,
    )
    {
    }

    public function handle(stdClass $data): void
    {
        if (empty($data)) {
            return;
        }

        match ($data->webhookType) {
            'incomingSbpPayment' => $this->createIncomingSbpPayment($data),
            'acquiringInternetPayment' => $this->createAcquiringInternetPayment($data),
        };

    }

    public function handleJsonPayments(stdClass $data): void
    {

        if (empty($data)) {
            return;
        }

        match ($data->webhookType) {
            'incomingSbpPayment' => $this->upsertIncomingSbpPayment($data),
            'acquiringInternetPayment' => $this->upsertAcquiringInternetPayment($data),
        };

    }

    private function createIncomingSbpPayment(stdClass $data): void
    {
        $model = new TochkaBankIncomingSbpPayment();
        $id = $model->query()->create($this->getIncomingSbpPaymentArray($data))->id;

        $this->issueReceiptService->handle(['type' => TochkaBankPaymentTypes::IncomingSbpPayment->value, 'id' => $id]);
    }

    private function createAcquiringInternetPayment(stdClass $data): void
    {
        $model = new TochkaBankAcquiringInternetPayment();
        $id = $model->query()->create($this->getAcquiringInternetPaymentArray($data))->id;

        $this->issueReceiptService->handle(['type' => TochkaBankPaymentTypes::AcquiringInternetPayment->value, 'id' => $id]);
    }

    private function getIncomingSbpPaymentArray(stdClass $data): array
    {
        return [
            'operation_id' => $data->operationId,
            'qrc_id' => $data->qrcId,
            'amount' => $data->amount,
            'payer_mobile_number' => $data->payerMobileNumber,
            'payer_name' => $data->payerName,
            'brand_name' => $data->brandName,
            'merchant_id' => $data->merchantId,
            'purpose' => $data->purpose ?? 'Графический дизайн',
            'customer_code' => $data->customerCode,
        ];
    }

    private function getAcquiringInternetPaymentArray(stdClass $data): array
    {
        return [
            'customer_code' => $data->customerCode,
            'amount' => $data->amount,
            'payment_type' => $data->paymentType,
            'operation_id' => $data->operationId,
            'transaction_id' => $data->transactionId ?? null,
            'purpose' => $data->purpose,
            'qrc_id' => $data->qrcId ?? null,
            'payer_name' => $data->payerName ?? null,
        ];
    }

    private function upsertIncomingSbpPayment(stdClass $data): void
    {
        $model = new TochkaBankIncomingSbpPayment();
        $array = $this->getIncomingSbpPaymentArray($data);
        if(!is_null($model->query()->limit(1000)->orderBy('created_at', 'DESC')->where($array)->first()))
        {
            return;
        }

       $model->query()->create($array);
    }

    private function upsertAcquiringInternetPayment(stdClass $data) : void
    {
        $model = new TochkaBankAcquiringInternetPayment();
        $array = $this->getAcquiringInternetPaymentArray($data);
        if(!is_null($model->query()->limit(1000)->orderBy('created_at', 'DESC')->where($array)->first()))
        {
            return;
        }

        $model->query()->create($array);
    }
}
