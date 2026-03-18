<?php

namespace App\Services\Telegram\Webhook\InlineQuery;

use App\Facades\Telegram;
use App\Services\Telegram\Webhook\TelegramWebhook;

class InlineQuery extends TelegramWebhook
{
    public function run()
    {
        return Telegram::answerStartInlineQuery(
            $this->request
        )->send($this->request->headers->all());
    }
}
