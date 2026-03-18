<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Services\Telegram\Webhook\Realization;
use App\Services\Telegram\Webhook\TelegramWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class TelegramWebhookController extends Controller
{
    public function index(
        Request $request,
        TelegramWebhook $webhook,
        Realization $realization,
    )
    {
        $secrets = Bot::query()->get()->pluck('x_telegram_bot_api_secret_token')->toArray();
        if(!in_array($request->header('X-Telegram-Bot-Api-Secret-Token'), $secrets))
        {
            return true;
        }

        $path = $realization->take($request);

        if(!$path)
        {
            $webhook->run();
            return true;
        }

        App::make($path)->run();

        return true;
    }
}
