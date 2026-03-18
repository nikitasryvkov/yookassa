<?php

namespace App\Services\Telegram\Webhook\Text;

use App\Services\Telegram\Webhook\TelegramWebhook;
use Illuminate\Support\Facades\Log;

class Text extends TelegramWebhook
{
    public function run()
    {
        Log::info('Не сработал ни один take', $this->request->headers->all());
    }
}
