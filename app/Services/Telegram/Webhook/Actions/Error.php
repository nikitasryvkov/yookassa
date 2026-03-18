<?php

namespace App\Services\Telegram\Webhook\Actions;

use App\Facades\Telegram;

class Error
{
    public function sendErrorMessageInline(string $inlineMessageId, array $request): void
    {
        Telegram::editInlineMessage(
            'Возникла ошибка',
            $inlineMessageId
        )->send($request);
    }
}
