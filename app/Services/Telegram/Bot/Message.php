<?php

namespace App\Services\Telegram\Bot;

use Illuminate\Support\Facades\Log;

class Message extends Bot
{
    protected array $data;
    protected string $method;

    public function message(string $chatId, string $text, ?string $replyId = null): self
    {
        $this->method = 'sendMessage';

        Log::info('message ' . $text . ' ' . $chatId);

        $this->data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'html',
            'link_preview_options' => [
                'is_disabled' => false,
            ]
        ];

        if($replyId) {
            $this->data['reply_parameters'] = [
                'message_id' => $replyId,

            ];
        }

        return $this;
    }

    public function editMessage(string $chatId, string $text, int $messageId): self
    {
        $this->method = 'editMessageText';

        $this->data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'html',
            'message_id' => $messageId,
        ];

        return $this;
    }

    public function editInlineMessage(string $text, string $inlineMessageId): self
    {
        $this->method = 'editMessageText';

        $this->data = [
            'inline_message_id' => $inlineMessageId,
            'text' => $text,
            'parse_mode' => 'html',
        ];

        return $this;
    }
}
