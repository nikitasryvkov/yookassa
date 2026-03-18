<?php

namespace App\Services;

use App\Models\UrlPayment;
use App\Services\TochkaBank\TbApiService;
use App\Services\YooKassa\YooKassaApiException;
use App\Services\YooKassa\YooKassaClient;
use Illuminate\Support\Facades\Log;

class PaymentUrlCreateService
{
    public function __construct(
        private readonly TbApiService $tbApiService,
        private readonly YooKassaClient $yooKassaClient,
    )
    {
    }

    public function handle(array $data): void
    {
        match ($data['payment_method_id']) {
            '1' => $this->createCardUrl($data),
            '2' => $this->createQrUrl($data),
            '3' => $this->createYandexUrl($data),
            '4' => $this->createYooKassaUrl($data),
        };
    }

    private function createYandexUrl(array $data): void
    {
        $orderId = time() . '_' . $data['total'];
        $yandexSplitData = $this->tbApiService->createYandexSplitPaymentOperation(
            $data['total'],
            $orderId,
            $data['user']['payment_point']['yandex_token'],
            $data['user']['payment_point']['payment_purpose']
        );

        if(empty($yandexSplitData)) {
            return;
        }

        UrlPayment::query()->create([
            'user_id' => $data['user_id'],
            'user_type_id' => $data['user_type_id'],
            'url' => $yandexSplitData['data']['paymentUrl'],
            'system_id' => $orderId,
            'system_status' => 'created',
            'sum' => $data['sum'],
            'total' => $data['total'],
            'commission' => $data['commission'],
            'payment_point_id' => $data['payment_point_id'],
            'payment_method_id' => $data['payment_method_id'],
            'yandex_token' => $data['user']['payment_point']['yandex_token'],
            'agent_commission_sum' => empty($data['user']['agent_commission_rate']) ? 0 : number_format(($data['sum'] * $data['user']['agent_commission_rate'] / 100), 2, '.', ''),
            'agent_commission_rate' => $data['user']['agent_commission_rate'],
        ]);
    }

    private function createQrUrl(array $data): void
    {
        $qrCodeData = $this->tbApiService->registerQrCode(
            $data['total'],
            $data['user']['payment_point']['merchant_id'],
            $data['user']['payment_point']['account_id'],
            $data['user']['payment_point']['payment_purpose']
        );

        if(empty($qrCodeData)) {
            return;
        }

        UrlPayment::query()->create([
            'user_id' => $data['user_id'],
            'user_type_id' => $data['user_type_id'],
            'url' => $qrCodeData['Data']['payload'],
            'system_id' => $qrCodeData['Data']['qrcId'],
            'system_status' => 'created',
            'sum' => $data['sum'],
            'total' => $data['total'],
            'commission' => $data['commission'],
            'payment_point_id' => $data['payment_point_id'],
            'payment_method_id' => $data['payment_method_id'],
            'agent_commission_sum' => empty($data['user']['agent_commission_rate']) ? 0 : number_format(($data['sum'] * $data['user']['agent_commission_rate'] / 100), 2, '.', ''),
            'agent_commission_rate' => $data['user']['agent_commission_rate'],
        ]);
    }

    private function createCardUrl(array $data): void
    {
        $cardPaymentData = $this->tbApiService->createCardSbpPaymentOperation(
            $data['total'],
            $data['user']['payment_point']['customer_code'],
            $data['user']['payment_point']['payment_purpose']
        );

        if(empty($cardPaymentData)) {
            return;
        }

        UrlPayment::query()->create([
            'user_id' => $data['user_id'],
            'user_type_id' => $data['user_type_id'],
            'url' => $cardPaymentData['Data']['paymentLink'],
            'system_id' => $cardPaymentData['Data']['operationId'],
            'system_status' => 'created',
            'sum' => $data['sum'],
            'total' => $data['total'],
            'commission' => $data['commission'],
            'payment_point_id' => $data['payment_point_id'],
            'payment_method_id' => $data['payment_method_id'],
            'agent_commission_sum' => empty($data['user']['agent_commission_rate']) ? 0 : number_format(($data['sum'] * $data['user']['agent_commission_rate'] / 100), 2, '.', ''),
            'agent_commission_rate' => $data['user']['agent_commission_rate'],
        ]);
    }

    private function createYooKassaUrl(array $data): void
    {
        $idempotenceKey = $this->yooKassaClient->generateIdempotenceKey();

        $payload = [
            'amount' => [
                // ЮKassa ожидает строку с 2 знаками после запятой
                'value' => number_format((float) $data['total'], 2, '.', ''),
                'currency' => 'RUB',
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => config('yookassa.return_url'),
            ],
            'capture' => true,
            'description' => $data['user']['payment_point']['payment_purpose'] ?? 'Оплата',
        ];

        try {
            $response = $this->yooKassaClient->createPayment($payload, $idempotenceKey);
        } catch (YooKassaApiException $e) {
            Log::error('YooKassa createPayment failed: ' . $e->getMessage(), [
                'statusCode' => $e->statusCode,
                'response' => $e->response,
            ]);
            return;
        }

        $confirmationUrl = $response['confirmation']['confirmation_url'] ?? null;
        $paymentId = $response['id'] ?? null;
        $status = $response['status'] ?? 'pending';

        if (empty($confirmationUrl) || empty($paymentId)) {
            Log::error('YooKassa createPayment returned unexpected payload', ['response' => $response]);
            return;
        }

        UrlPayment::query()->create([
            'user_id' => $data['user_id'],
            'user_type_id' => $data['user_type_id'],
            'url' => $confirmationUrl,
            'system_id' => $paymentId,
            'system_status' => $status,
            'idempotence_key' => $idempotenceKey,
            'sum' => $data['sum'],
            'total' => $data['total'],
            'commission' => $data['commission'],
            'payment_point_id' => $data['payment_point_id'],
            'payment_method_id' => $data['payment_method_id'],
            'provider_payload' => $response,
            'agent_commission_sum' => empty($data['user']['agent_commission_rate']) ? 0 : number_format(($data['sum'] * $data['user']['agent_commission_rate'] / 100), 2, '.', ''),
            'agent_commission_rate' => $data['user']['agent_commission_rate'],
        ]);
    }
}
