<?php

namespace App\Services\Telegram\Webhook;

use App\Services\Telegram\Webhook\Actions\Error;
use App\Services\TochkaBank\TbApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhook
{
    public function __construct(
        protected Request $request,
        protected TbApiService $tbApiService,
        protected Error $errorAction,
        protected ?int $chatId = null,
    )
    {
        $this->setChatId();
    }

    public function run()
    {
        Log::info('Не удалось обработать сообщение', $this->request->all());
        Log::info('Чат ID' . $this->chatId);
    }

    private function setChatId(): void
    {
        if($this->request->input('callback_query')) {
            $this->chatId = $this->request->input('callback_query')['from']['id'];
        }

        if($this->request->input('message')) {
            $this->chatId = $this->request->input('message')['from']['id'];
        }
    }
}
