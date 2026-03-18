<?php

namespace App\Services\Telegram\Helpers;

use Illuminate\Support\Facades\Log;

class InlineQueryResultArticle
{
    public static array $articles = [

    ];

    public static function add(
        array $data,
        string $title,
        array $inputMessageContent,
        ?string $description,
        array $replyMarkup,
    ): void
    {

        $array = [
            'type' => 'article',
            'title' => $title,
            'id' => $data['id'],
            'input_message_content' => $inputMessageContent,
        ];

        if(!empty($replyMarkup)) {
            $array['reply_markup'] = $replyMarkup;
        }

        if(!empty($description)) {
            $array['description'] = $description;
        }

        self::$articles[] = $array;
    }
}
