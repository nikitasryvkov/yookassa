<?php

namespace App\Services\Telegram\Bot;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class Bot
{
    public function send(array $request): Response
    {
        $token = \App\Models\Bot::query()
            ->where('x_telegram_bot_api_secret_token', $request['x-telegram-bot-api-secret-token'])->first()->token;
        return Http::post(
            'https://api.telegram.org/bot' . $token . '/' . $this->method,
            $this->data
        );
    }
}
