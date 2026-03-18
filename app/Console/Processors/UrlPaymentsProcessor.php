<?php

namespace App\Console\Processors;

use App\Enums\PaymentSource;
use App\Models\AgentCommission;
use App\Models\UrlPayment;
use App\Services\TochkaBank\TbApiService;
use App\Services\YooKassa\YooKassaApiException;
use App\Services\YooKassa\YooKassaClient;
use Illuminate\Support\Facades\Log;

class UrlPaymentsProcessor
{
    public function __construct(
        private readonly TbApiService $tbApiService,
        private readonly YooKassaClient $yooKassaClient,
    ) {
    }

    public function run()
    {
        UrlPayment::query()
            ->whereNull('payed')
            ->where('system_status', '!=', 'forgotten')
            ->where('created_at', '>', now()->subHours(121))
            ->orderBy('id')
            ->chunkById(200, function ($paymentUrls): void {
                foreach ($paymentUrls as $url) {
            try {
                $status = match ($url->payment_method_id) {
                    1 => $this->tbApiService->getCardPaymentStatus($url->system_id)['Data']['Operation'][0]['status'] ?? 'forgotten',
                    2 => $this->tbApiService->getQrCodePaymentStatus([$url->system_id])['Data']['paymentList'][0]['status'] ?? 'forgotten',
                    3 => strtolower($this->tbApiService->getYandexSplitPaymentStatus($url->system_id, $url->yandex_token)['data']['order']['paymentStatus'] ?? 'forgotten'),
                    4 => $this->yooKassaClient->getPayment($url->system_id)['status'] ?? 'forgotten',
                };

                if(in_array($status, ['success', 'captured', 'APPROVED', 'Accepted', 'succeeded'], true))
                {
                    $url->system_status = $status;
                    $url->payed = now();
                    $url->save();

                    AgentCommission::query()->firstOrCreate(
                        [
                            'source' => PaymentSource::Url->value,
                            'request_id' => (string) $url->system_id,
                        ],
                        [
                            'user_id' => $url->user_id,
                            'sum' => $url->sum,
                            'total' => $url->total,
                            'agent_commission_sum' => $url->agent_commission_sum,
                            'agent_commission_rate' => $url->agent_commission_rate,
                            'payment_point_id' => $url->payment_point_id,
                            'payment_method_id' => $url->payment_method_id,
                        ]
                    );
                }

                if ($url->payment_method_id === 4 && $status === 'canceled') {
                    $url->system_status = 'forgotten';
                    $url->save();
                }

                if($status === 'forgotten') {
                    $url->system_status = $status;
                    $url->save();
                }
            } catch (YooKassaApiException $e) {
                Log::error("Ошибка YooKassa при проверке статуса URL #$url->id: " . $e->getMessage(), [
                    'statusCode' => $e->statusCode,
                    'response' => $e->response,
                ]);
            } catch (\Throwable $e) {
                Log::error("Ошибка проверки статуса оплаты для URL #$url->id: " . $e->getMessage());
            }
                }
            });
    }
}
