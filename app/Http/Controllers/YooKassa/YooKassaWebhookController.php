<?php

namespace App\Http\Controllers\YooKassa;

use App\Enums\PaymentSource;
use App\Http\Controllers\Controller;
use App\Models\AgentCommission;
use App\Models\UrlPayment;
use App\Services\YooKassa\YooKassaApiException;
use App\Services\YooKassa\YooKassaClient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

final class YooKassaWebhookController extends Controller
{
    public function __invoke(Request $request, YooKassaClient $client): Response
    {
        $executed = RateLimiter::attempt(
            'yookassa webhook',
            $perMinute = 60,
            function () {
                // no-op
            }
        );

        if (!$executed) {
            return response('Too many requests', 429);
        }

        $expectedToken = config('yookassa.webhook_token');
        if (!empty($expectedToken)) {
            $token = (string) $request->header('X-Webhook-Token', '');
            if (!hash_equals($expectedToken, $token)) {
                return response('Forbidden', 403);
            }
        }

        $payload = $request->json()->all();
        if (($payload['type'] ?? null) !== 'notification') {
            return response('Bad Request', 400);
        }
        $event = $payload['event'] ?? null;
        $object = $payload['object'] ?? null;

        if (!is_string($event) || !is_array($object)) {
            return response('Bad Request', 400);
        }

        $paymentId = match ($event) {
            'refund.succeeded' => $object['payment_id'] ?? null,
            default => $object['id'] ?? null,
        };
        if (!is_string($paymentId) || $paymentId === '') {
            return response('Bad Request', 400);
        }

        $urlPayment = UrlPayment::query()
            ->where('payment_method_id', 4)
            ->where('system_id', $paymentId)
            ->first();

        if (!$urlPayment) {
            // Не считаем ошибкой: вебхук может прийти до сохранения или для другого потока.
            Log::warning('YooKassa webhook: payment not found in url_payments', [
                'event' => $event,
                'payment_id' => $paymentId,
            ]);
            return response('OK', 200);
        }

        // Рекомендация ЮKassa: перепроверить актуальный статус объекта через API.
        try {
            $apiPayment = $client->getPayment($paymentId);
        } catch (YooKassaApiException $e) {
            Log::error('YooKassa webhook: failed to fetch payment status from API', [
                'payment_id' => $paymentId,
                'message' => $e->getMessage(),
                'statusCode' => $e->statusCode,
                'response' => $e->response,
            ]);
            return response('OK', 200);
        }

        $apiStatus = $apiPayment['status'] ?? null;

        if ($event === 'payment.succeeded') {
            if ($apiStatus !== 'succeeded') {
                Log::warning('YooKassa webhook mismatch: payment.succeeded but API status differs', [
                    'payment_id' => $paymentId,
                    'api_status' => $apiStatus,
                ]);
                return response('OK', 200);
            }
            $urlPayment->system_status = 'succeeded';
            $urlPayment->payed ??= now();
            $urlPayment->provider_payload = $payload;
            $urlPayment->save();

            AgentCommission::query()->firstOrCreate(
                [
                    'source' => PaymentSource::Url->value,
                    'request_id' => (string) $urlPayment->system_id,
                ],
                [
                    'user_id' => $urlPayment->user_id,
                    'sum' => $urlPayment->sum,
                    'total' => $urlPayment->total,
                    'agent_commission_sum' => $urlPayment->agent_commission_sum,
                    'agent_commission_rate' => $urlPayment->agent_commission_rate,
                    'payment_point_id' => $urlPayment->payment_point_id,
                    'payment_method_id' => $urlPayment->payment_method_id,
                ]
            );

            return response('OK', 200);
        }

        if ($event === 'payment.canceled') {
            if ($apiStatus !== 'canceled') {
                Log::warning('YooKassa webhook mismatch: payment.canceled but API status differs', [
                    'payment_id' => $paymentId,
                    'api_status' => $apiStatus,
                ]);
                return response('OK', 200);
            }
            $urlPayment->system_status = 'forgotten';
            $urlPayment->provider_payload = $payload;
            $urlPayment->save();
            return response('OK', 200);
        }

        if ($event === 'refund.succeeded') {
            $refundId = $object['id'] ?? null;
            if (is_string($refundId) && $refundId !== '') {
                $urlPayment->refund_id = $refundId;
            }
            $urlPayment->refunded_at = now();
            $urlPayment->provider_payload = $payload;
            $urlPayment->save();
            return response('OK', 200);
        }

        // Неизвестные события не падаем, просто фиксируем.
        Log::info('YooKassa webhook: unhandled event', [
            'event' => $event,
            'payment_id' => $paymentId,
        ]);

        return response('OK', 200);
    }
}

