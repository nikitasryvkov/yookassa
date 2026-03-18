<?php

namespace App\Services\Telegram\Bot;

class Buttons extends Bot
{
    protected array $data;
    protected string $method;

    public function buttons(string $chatId, string $text, array $buttons, ?string $replyId = null): self
    {
        $this->method = 'sendMessage';

        $this->data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'html',
            'reply_markup' => json_encode($buttons),
        ];

        if($replyId) {
            $this->data['reply_parameters'] = [
                'message_id' => $replyId,

            ];
        }

        return $this;
    }

    public function editButtons(string $chatId, string $text, array $buttons, int $messageId): self
    {
        $this->method = 'editMessageText';

        $this->data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'html',
            'reply_markup' => json_encode($buttons),
            'message_id' => $messageId,
        ];

        return $this;
    }

    public function editInlineButtons(string $inlineMessageId, string $text, array $buttons): self
    {
        $this->method = 'editMessageText';

        $this->data = [
            'inline_message_id' => $inlineMessageId,
            'text' => $text,
            'parse_mode' => 'html',
            'reply_markup' => json_encode($buttons),
        ];

        return $this;
    }
}

