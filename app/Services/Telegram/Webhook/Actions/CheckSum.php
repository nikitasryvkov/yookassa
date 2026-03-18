<?php

namespace App\Services\Telegram\Webhook\Actions;

use App\Facades\Telegram;

class CheckSum
{
    public function handleInline(?string $sum, string $messageId, array $request): bool
    {
        if(!is_numeric($sum) || $sum < 1) {

            Telegram::editInlineMessage('Сумма не верна', $messageId)->send($request);
            return false;
        }

        return true;
    }
}
