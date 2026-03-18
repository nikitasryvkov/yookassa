<?php

namespace App\Services\Telegram\Webhook\Actions\YandexSplit;

use App\Facades\Telegram;
use App\Jobs\BotCheckYandexSplitPaymentStatusJob;
use App\Models\User;
use App\Services\Telegram\Helpers\InlineKeyboardButton;
use App\Services\Telegram\Webhook\TelegramWebhook;

class CreateYandexSplitPayment extends TelegramWebhook
{
    public function run()
    {
        $callbackQuery = $this->request->input('callback_query');
        $data = json_decode($callbackQuery['data']);

        $sum = $data->sum;
        $user = User::query()->where('id', $data->uid)->with('paymentPoint')->first();
        $commission = $user->yandex_commission_rate;
        $total = number_format((float)($sum + $sum * $commission / 100), 2, '.', '');
        $orderId = time() . '_' . $total;
        $yandexSplitData = $this->tbApiService->createYandexSplitPaymentOperation(
            $total,
            $orderId,
            $user->paymentPoint->yandex_token,
            $user->paymentPoint->payment_purpose,
        );

        if(empty($yandexSplitData)) {
            $this->errorAction->sendErrorMessageInline($callbackQuery['inline_message_id'], $this->request->headers->all());
            return;
        }

        $paymentLink = $yandexSplitData['data']['paymentUrl'];

        InlineKeyboardButton::$buttons = [];
        InlineKeyboardButton::link(
            'Статус : ожидает оплаты',
            $paymentLink
        );
        Telegram::editInlineButtons(
            $callbackQuery['inline_message_id'],
            "Перейдите по ссылке для оплаты счета Яндекс сплит на сумму $total руб.",
            InlineKeyboardButton::$buttons
        )->send($this->request->headers->all());

        //проверка статуса
        BotCheckYandexSplitPaymentStatusJob::dispatch(
            $orderId,
            $paymentLink,
            $callbackQuery['inline_message_id'],
            $callbackQuery['from'],
            $total,
            $commission,
            $sum,
            $data->uid,
            $this->request->headers->all(),0,
        )->onQueue('bot');
    }
}
