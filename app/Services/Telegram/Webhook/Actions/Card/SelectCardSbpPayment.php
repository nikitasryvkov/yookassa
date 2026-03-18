<?php

namespace App\Services\Telegram\Webhook\Actions\Card;

use App\Facades\Telegram;
use App\Models\Bot;
use App\Services\Telegram\Helpers\InlineKeyboardButton;
use App\Services\Telegram\Webhook\Actions\CheckSum;
use App\Services\Telegram\Webhook\TelegramWebhook;

class SelectCardSbpPayment extends TelegramWebhook
{
    public function run()
    {
        $callbackQuery = $this->request->input('callback_query');

        $data = json_decode($callbackQuery['data']);
        $sum =  $data->sum;

        $checkSum = new CheckSum();
        $checkOk = $checkSum->handleInline($sum, $callbackQuery['inline_message_id'], $this->request->headers->all());

        if(!$checkOk) {
            return;
        }

        $bot = Bot::query()
            ->where('x_telegram_bot_api_secret_token', $this->request->headers->all()['x-telegram-bot-api-secret-token'])->first();

        InlineKeyboardButton::add(
            $bot->name . 'Card',
            [
                'action' => 'CreateCardBill',
                'sum' => $sum,
                'uid' => $data->uid
            ],
        );
        Telegram::editInlineButtons(
            $callbackQuery['inline_message_id'],
            'Выберите платёж',
            InlineKeyboardButton::$buttons
        )->send($this->request->headers->all());
    }
}
