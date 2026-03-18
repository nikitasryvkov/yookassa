<?php

namespace App\Services\Telegram\Webhook\Actions\Card;

use App\Facades\Telegram;
use App\Jobs\BotCheckCardPaymentStatusJob;
use App\Models\User;
use App\Services\Telegram\Helpers\InlineKeyboardButton;
use App\Services\Telegram\Webhook\TelegramWebhook;

class CreateCardBill extends TelegramWebhook
{
    public function run()
    {
        $callbackQuery = $this->request->input('callback_query');
        $data = json_decode($callbackQuery['data']);

        $sum = $data->sum;
        $user = User::query()->where('id', $data->uid)->with('paymentPoint')->first();
        $commission = $user->card_commission_rate;
        $total = number_format((float)($sum + $sum * $commission / 100), 2, '.', '');

        $cardPaymentData = $this->tbApiService->createCardSbpPaymentOperation(
            $total,
            $user->paymentPoint->customer_code,
            $user->paymentPoint->payment_purpose,
        );

        if(empty($cardPaymentData )) {
            $this->errorAction->sendErrorMessageInline($callbackQuery['inline_message_id'], $this->request->headers->all());
            return;
        }

        $operationId = $cardPaymentData['Data']['operationId'];
        $paymentLink = $cardPaymentData['Data']['paymentLink'];

        InlineKeyboardButton::$buttons = [];
        InlineKeyboardButton::link(
            'Статус : ожидает оплаты',
            $paymentLink
        );
        Telegram::editInlineButtons(
            $callbackQuery['inline_message_id'],
            "Перейдите по ссылке для оплаты картой на сумму $total руб.",
            InlineKeyboardButton::$buttons
        )->send($this->request->headers->all());

        //проверка статуса
        BotCheckCardPaymentStatusJob::dispatch(
            $operationId,
            $paymentLink,
            $callbackQuery['inline_message_id'],
            $callbackQuery['from'],
            $total,
            $commission,
            $sum,
            $data->uid,
            $this->request->headers->all(),
            0
        )->onQueue('bot');
    }
}
