<?php

namespace App\Services\TochkaBank;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TbApiService
{
    private readonly string $url;

    public function __construct()
    {
        $this->url = 'https://enter.tochka.com/uapi/';
    }

    public function registerQrCode(string $amount, string $merchantId, string $accountId, string $paymentPurpose): array
    {
        $method = 'sbp/v1.46/qr-code/merchant/';

        try {
            $response = Http::withToken(config('app.tb.tb_token'))
                ->acceptJson()
                ->post(
                    $this->url . $method . $merchantId . '/' . $accountId,
                    [
                        'Data' => [
                            'amount' => (int)($amount * 100),
                            'currency' => 'RUB',
                            'paymentPurpose' => $paymentPurpose,
                            'qrcType' => '02',
                            'imageParams' => [
                                'width' => 800,
                                'height' => 800,
                                'mediaType' => 'image/png'
                            ],
                        ]
                    ]
                );

        } catch (Throwable $e) {
            Log::error('Исключение при запросе Qr кода:' . $e->getMessage());
            return [];
        }

        return $response->json();
    }

    public function getQrCodePaymentStatus(array $qrCodeIds)
    {
        $qrCodesIdsString = implode(',', $qrCodeIds);
        $method = "sbp/v1.46/qr-codes/$qrCodesIdsString/payment-status";

        try {

            $response = Http::withToken(config('app.tb.tb_token'))
                ->acceptJson()
                ->get($this->url . $method);

        } catch (Throwable $e) {
            Log::error('Исключение при запросе статуса оплаты по Qr коду:' . $e->getMessage());
            return [];
        }

        return $response->json();
    }

    public function createCardSbpPaymentOperation(string $amount, string $customerCode, string $paymentPurpose): array
    {
        $method = 'acquiring/v1.46/payments';
        try {
            $response = Http::withToken(config('app.tb.tb_token'))
                ->acceptJson()
                ->post(
                    $this->url . $method,
                    [
                        'Data' => [
                            'customerCode' => $customerCode,
                            'amount' => $amount,
                            'purpose' => $paymentPurpose,
                            'paymentMode' => ['card'],
                        ]
                    ]
                );

        } catch (Throwable $e) {
            Log::error('Исключение при запросе создания ссылки на оплату:' . $e->getMessage());
            return [];
        }

        return $response->json();
    }

    public function getCardPaymentStatus(string $paymentId)
    {
        $method = "acquiring/v1.46/payments/$paymentId";

        try {

            $response = Http::withToken(config('app.tb.tb_token'))
                ->acceptJson()
                ->get($this->url . $method);

        } catch (Throwable $e) {
            Log::error('Исключение при запросе статуса оплаты по карте:' . $e->getMessage());
            return [];
        }

        return $response->json();
    }

    public function createYandexSplitPaymentOperation(string $amount, string $orderId, string $yandexToken, string $paymentPurpose): array
    {
        try {
            $response = Http::withHeader('Authorization', 'Api-Key ' . $yandexToken)
                ->acceptJson()
                ->post(
                    'https://pay.yandex.ru/api/merchant/v1/orders',
                    [
                        "availablePaymentMethods" => [ 'SPLIT'],
                        'currencyCode' => 'RUB',
                        'orderId' => $orderId,
                        "cart" => [
                                'items' => [
                                [
                                    "productId" => "1",
                                    'quantity' => ['count' => 1],
                                    'title' => $paymentPurpose,
                                    'total' => $amount,
                                ],
                            ],
                            'total' => ["amount" => $amount],
                        ],
                    ]
                );

        } catch (Throwable $e) {
            Log::error('Исключение при запросе создания ссылки на оплату yandex split:' . $e->getMessage());
            return [];
        }

        return $response->json();
    }

    public function getYandexSplitPaymentStatus(string $paymentId, string $yandexToken)
    {
        try {
            $response = Http::withHeader('Authorization', 'Api-Key ' . $yandexToken)
                ->acceptJson()
                ->get('https://pay.yandex.ru/api/merchant/v1/orders/' . $paymentId);

        } catch (Throwable $e) {
            Log::error('Исключение при запросе статуса оплаты по yandex split:' . $e->getMessage());
            return [];
        }

        return $response->json();
    }

    public function createPaymentForSign(array $data)
    {
        $method = 'payment/v1.46/for-sign';
        try {
            $response = Http::withToken(config('app.tb.tb_token'))
                ->acceptJson()
                ->post(
                    $this->url . $method,
                    [
                        'Data' => [
                            'accountCode' => $data['accountCode'],
                            'bankCode' => $data['bankCode'],
                            'counterpartyBankBic' => $data['counterpartyBankBic'],
                            'counterpartyAccountNumber' => $data['counterpartyAccountNumber'],
                            'counterpartyINN' => 233010036207,
                            'counterpartyName' => $data['counterpartyName'],
                            'paymentAmount' => $data['paymentAmount'],
                            'paymentDate' => $data['paymentDate'],
                            'paymentPurpose' => $data['paymentPurpose'] . ' Без НДС',
                        ]
                    ]
                );

        } catch (Throwable $e) {
            Log::error('Исключение при запросе оплаты комиссии:' . $e->getMessage());
            return [];
        }

        return $response->json();
    }

    public function getPaymentStatus(string $requestId)
    {
        $method = 'payment/v1.46/status/' . $requestId;
        try {
            $response = Http::withToken(config('app.tb.tb_token'))
                ->acceptJson()
                ->get($this->url . $method);

        } catch (Throwable $e) {
            Log::error('Исключение при запросе статуса:' . $e->getMessage());
            return [];
        }

        return $response->json();
    }

    public function getAccountBalance(string $accountId)
    {
        $method = 'open-banking/v1.46/accounts/' . $accountId . '/balances';
        try {
            $response = Http::withToken(config('app.tb.tb_token'))
                ->acceptJson()
                ->get($this->url . $method);

        } catch (Throwable $e) {
            Log::error("Исключение при запросе баланса счёта $accountId :" . $e->getMessage());
            return [];
        }

        return $response->json();
    }
}
