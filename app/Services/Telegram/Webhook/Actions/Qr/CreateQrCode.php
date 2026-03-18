<?php

namespace App\Services\Telegram\Webhook\Actions\Qr;

use App\Facades\Telegram;
use App\Jobs\BotCheckQRCodePaymentStatusJob;
use App\Models\User;
use App\Services\Telegram\Helpers\InlineKeyboardButton;
use App\Services\Telegram\Webhook\TelegramWebhook;

class CreateQrCode extends TelegramWebhook
{
    public function run()
    {
        $callbackQuery = $this->request->input('callback_query');
        $data = json_decode($callbackQuery['data']);

        $sum = $data->sum;
        $user = User::query()->where('id', $data->uid)->with('paymentPoint')->first();
        $commission = $user->qr_commission_rate;
        $total = number_format((float)($sum + $sum * $commission / 100), 2, '.', '');

        $qrCodeData = $this->tbApiService->registerQrCode(
            $total,
            $user->paymentPoint->merchant_id,
            $user->paymentPoint->account_id,
            $user->paymentPoint->payment_purpose,
        );

        if(empty($qrCodeData)) {
            $this->errorAction->sendErrorMessageInline($callbackQuery['inline_message_id'], $this->request->headers->all());
            return;
        }

        $qrCodeId = $qrCodeData['Data']['qrcId'];
        $qrCodeLink = $qrCodeData['Data']['payload'];

        InlineKeyboardButton::$buttons = [];
        InlineKeyboardButton::link(
            'Статус : ожидает оплаты',
            $qrCodeLink
        );
        Telegram::editInlineButtons(
            $callbackQuery['inline_message_id'],
            "Перейдите по ссылке ниже для оплаты по СБП на сумму $total руб.",
            InlineKeyboardButton::$buttons
        )->send($this->request->headers->all());

        //проверка статуса
        BotCheckQRCodePaymentStatusJob::dispatch(
            $qrCodeId,
            $qrCodeLink,
            $callbackQuery['inline_message_id'],
            $callbackQuery['from'],
            $total,
            $commission,
            $sum,
            $data->uid,
            $this->request->headers->all(),
            0,
        )->onQueue('bot');
    }

}
