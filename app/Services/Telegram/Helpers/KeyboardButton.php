<?php

namespace App\Services\Telegram\Helpers;

class KeyboardButton
{
    private static int $button_number = 1;

    public static array $buttons = [
        'keyboard' => [

        ],
        'resize_keyboard' => true,
    ];

    public static function add(string $text, int $row = 1): void
    {
        self::$button_number++;

        self::$buttons['keyboard'][$row-1][] = [
            'text' => $text,
        ];
    }

    public static function remove(): void
    {
        self::$buttons = [
            'remove_keyboard' =>true,
        ];
    }
}
