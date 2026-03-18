<?php

namespace App\Services\Telegram\Bot;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class File extends Bot
{
    protected array $data;
    protected string $method;
    private $file;
    private string $fileName;
    private string $type;

    public function document(string $chatId, $file, string $fileName, ?string $replyId = null): static
    {
        $this->method = 'sendDocument';
        $this->type = 'document';
        $this->file = $file;
        $this->fileName = $fileName;

        $this->data = [
            'chat_id' => $chatId,

        ];

        if($replyId) {
            $this->data['reply_parameters'] = [
                'message_id' => $replyId,

            ];
        }

        return $this;
    }

    public function photo(string $chatId, $file, string $fileName, ?string $replyId = null): static
    {
        $this->method = 'sendPhoto';
        $this->type = 'photo';
        $this->file = $file;
        $this->fileName = $fileName;

        $this->data = [
            'chat_id' => $chatId,

        ];

        if($replyId) {
            $this->data['reply_parameters'] = [
                'message_id' => $replyId,

            ];
        }

        return $this;
    }

    public function album(string $chatId, array $fileUrls, ?string $replyId = null)
    {
        $this->method = 'sendMediaGroup';
        $this->type = 'media';

        $this->data = [
            'chat_id' => $chatId,

        ];

        foreach ($fileUrls as $url)
        {
            $this->data['media'][] = [
                'media' => $url,
                'type' => 'photo',
            ];
        }

        if($replyId) {
            $this->data['reply_parameters'] = [
                'message_id' => $replyId,

            ];
        }

        return $this;
    }

    public function send(array $request): Response
    {
        if($this->file)
        {
            $token = \App\Models\Bot::query()
                ->where('x_telegram_bot_api_secret_token', $request['x_telegram_bot_api_secret_token'])->first()->token;
            return Http::attach($this->type, $this->file, $this->fileName)->post(
                'https://api.telegram.org/bot' . $token . '/' . $this->method,
                $this->data
            );
        }

        return parent::send($request);
    }
}
