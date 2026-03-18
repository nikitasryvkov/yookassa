<?php

namespace App\Services\Telegram\Helpers;

class InputMessageContent
{
    public array $content = [
        'message_text' => '',
    ];


    public function init(string $messageText, string $parseMode = null): self
    {
        $this->content['message_text'] = $messageText;
        if($parseMode) {
            $this->content['parse_mode'] = $parseMode;
        }

        return $this;
    }

    public function getContent(): array
    {
        return $this->content;
    }
}
