<?php

namespace App\Services\Telegram\Helpers;

class InlineKeyboardButton
{
    public static array $buttons = [
        'inline_keyboard' => [

        ]
    ];

    public static function add(string $text, array $data, int $row = 1): int
    {
        self::$buttons['inline_keyboard'][$row-1][] = [
            'text' => $text,
            'callback_data' => json_encode($data),
        ];

        return array_key_last(self::$buttons['inline_keyboard'][$row-1]);
    }

    public static function link(string $text, string $url, int $row = 1): void
    {
        self::$buttons['inline_keyboard'][$row-1][] = [
            'text' => $text,
            'url' => $url,
        ];
    }

    public static function reset(): void
    {
        self::$buttons = ['inline_keyboard' => []];
    }
}
