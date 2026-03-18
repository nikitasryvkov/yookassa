<?php

namespace App\Services\Telegram\Webhook\Commands;

use App\Facades\Telegram;
use App\Models\Bot;
use App\Services\Telegram\Webhook\TelegramWebhook;

class Start extends TelegramWebhook
{
    public function run()
    {
        $bot = Bot::query()
            ->where('x_telegram_bot_api_secret_token', $this->request->headers->all()['x-telegram-bot-api-secret-token'])->first();
        $text = $bot->start_text ?? 'Привет';
        return Telegram::message($this->chatId, $text)->send($this->request->headers->all());
    }
}
