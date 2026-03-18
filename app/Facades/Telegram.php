<?php

namespace App\Facades;

use App\Services\Telegram\Bot\Bot;
use App\Services\Telegram\Bot\Buttons;
use App\Services\Telegram\Bot\File;
use App\Services\Telegram\Bot\InlineQuery;
use App\Services\Telegram\Bot\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Message message(string $chatId, string $text, $replyId = null)
 * @method static Message editMessage(string $chatId, string $text, int $messageId)
 * @method static Message editInlineMessage(string $text, string $inlineMessageId)
 * @method static Buttons buttons(string $chatId, string $text, array $buttons)
 * @method static Buttons editButtons(string $chatId, string $text, array $buttons, int $messageId)
 * @method static Buttons editInlineButtons(string $inlineMessageId, string $text, array $buttons)
 * @method static File document(string $chatId, $file, string $fileName, $reply_id = null)
 * @method static File photo(string $chatId, $file, string $fileName, $reply_id = null)
 * @method static File album(string $chatId, array $fileUrls, $reply_id = null)
 * @method static InlineQuery answerStartInlineQuery(Request $request)
 * @method Bot send()
 */
class Telegram extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Telegram::class;
    }
}
