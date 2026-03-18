<?php

namespace App\Services\YooKassa;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

final class YooKassaClient
{
    private const BASE_URL = 'https://api.yookassa.ru/v3/';

    public function createPayment(array $payload, ?string $idempotenceKey = null): array
    {
        return $this->post('payments', $payload, $idempotenceKey);
    }

    public function getPayment(string $paymentId): array
    {
        return $this->get('payments/' . $paymentId);
    }

    public function createRefund(array $payload, ?string $idempotenceKey = null): array
    {
        return $this->post('refunds', $payload, $idempotenceKey);
    }

    public function generateIdempotenceKey(): string
    {
        // ЮKassa принимает до 64 символов.
        return Str::uuid()->toString();
    }

    private function get(string $path): array
    {
        return $this->request('get', $path, null, null);
    }

    private function post(string $path, array $payload, ?string $idempotenceKey): array
    {
        return $this->request('post', $path, $payload, $idempotenceKey);
    }

    private function request(string $method, string $path, ?array $payload, ?string $idempotenceKey): array
    {
        $shopId = config('yookassa.shop_id');
        $secretKey = config('yookassa.secret_key');

        if (empty($shopId) || empty($secretKey)) {
            throw new YooKassaApiException('YooKassa credentials are not configured (YOOKASSA_SHOP_ID/YOOKASSA_SECRET_KEY).');
        }

        try {
            $client = Http::withBasicAuth($shopId, $secretKey)
                ->acceptJson()
                ->asJson()
                ->timeout(30);

            if (!empty($idempotenceKey)) {
                $client = $client->withHeaders(['Idempotence-Key' => $idempotenceKey]);
            }

            $url = self::BASE_URL . ltrim($path, '/');
            $response = match (strtolower($method)) {
                'get' => $client->get($url),
                'post' => $client->post($url, $payload ?? []),
                default => throw new YooKassaApiException("Unsupported HTTP method: {$method}"),
            };
        } catch (ConnectionException $e) {
            throw new YooKassaApiException('YooKassa connection error: ' . $e->getMessage(), null, null, $e);
        } catch (Throwable $e) {
            throw new YooKassaApiException('YooKassa request failed: ' . $e->getMessage(), null, null, $e);
        }

        return $this->unwrap($response);
    }

    private function unwrap(Response $response): array
    {
        if ($response->successful()) {
            return $response->json() ?? [];
        }

        $status = $response->status();
        $body = $response->json();

        $message = 'YooKassa API error';
        if (is_array($body)) {
            $details = $body['description'] ?? $body['message'] ?? null;
            $id = $body['id'] ?? null;
            $code = $body['code'] ?? null;
            $message = trim(implode(' ', array_filter([
                $message . " ({$status})",
                $code ? "code={$code}" : null,
                $id ? "id={$id}" : null,
                $details,
            ])));
        } else {
            $message = $message . " ({$status}): " . $response->body();
        }

        throw new YooKassaApiException($message, $status, is_array($body) ? $body : null);
    }
}

