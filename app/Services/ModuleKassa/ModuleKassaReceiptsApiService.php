<?php

namespace App\Services\ModuleKassa;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ModuleKassaReceiptsApiService
{
    private string $url = 'https://service.modulpos.ru/api/fn';
    private string $version = 'v2';

    public function getApiServiceStatus(): array
    {
        $response = Http::withBasicAuth(
            config('app.other_api_auth.module_kassa_login'),
            config('app.other_api_auth.module_kassa_password'),
        )
            ->acceptJson()
            ->get(
                $this->url . '/' . $this->version . '/' . 'status',
                [

                ]
            );

        if ($response->failed()) {
            Log::error('Запрос на создание чека не прошёл: ' . $response->body());
            return [];
        };

        return $response->json();
    }

    public function issueReceipt(array $data)
    {
        try {
        $response = Http::withBasicAuth(
            config('app.other_api_auth.module_kassa_login'),
            config('app.other_api_auth.module_kassa_password'),
        )
            ->timeout(30)
            ->send(
                'post',
                $this->url . '/' . $this->version . '/' . 'doc',
                [
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                    'body' => json_encode($data, JSON_UNESCAPED_UNICODE),
                ]

            );

        if ($response->failed()) {
            Log::error('Запрос на создание чека не прошёл: ' . $response->body());
            return [];
        };

        return $response->json();
        } catch (ConnectionException $e) {
            Log::error('Ошибка соединения при отправке запроса на чек: ' . $e->getMessage());
            return [];
        } catch (\Exception $e) {
            Log::error('Неизвестная ошибка при отправке запроса на чек: ' . $e->getMessage());
            return [];
        }
    }

    public function getReceiptStatus(int $id): array
    {
        $response = Http::withBasicAuth(
            config('app.other_api_auth.module_kassa_login'),
            config('app.other_api_auth.module_kassa_password'),
        )
            ->acceptJson()
            ->get(
                $this->url . '/' . 'v1' . '/' . 'doc' . '/' . $id . '/status',
            );

        if ($response->failed()) {
            Log::error('Запрос на статус чека не прошёл: ' . $response->body());
        };

        return $response->json();
    }
}
