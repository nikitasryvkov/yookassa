<?php

namespace App\Services\Telegram\Webhook;

use App\Services\Telegram\Webhook\Actions\Card\CreateCardBill;
use App\Services\Telegram\Webhook\Actions\Card\SelectCardSbpPayment;
use App\Services\Telegram\Webhook\Actions\Qr\CreateQrCode;
use App\Services\Telegram\Webhook\Actions\Qr\SelectQrPayment;
use App\Services\Telegram\Webhook\Actions\YandexSplit\CreateYandexSplitPayment;
use App\Services\Telegram\Webhook\Actions\YandexSplit\SelectYandexSplitPayment;
use App\Services\Telegram\Webhook\Commands\Start;
use App\Services\Telegram\Webhook\InlineQuery\InlineQuery;
use App\Services\Telegram\Webhook\Text\Text;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Realization
{
    protected const COMMANDS = [
        '/start' => Start::class,
    ];

    protected const ACTIONS = [
        'SelectQrPayment' => SelectQrPayment::class,
        'CreateQrCode' => CreateQrCode::class,
        'SelectCardSbpPayment' => SelectCardSbpPayment::class,
        'CreateCardBill' => CreateCardBill::class,
        'SelectYandexSplitPayment' => SelectYandexSplitPayment::class,
        'CreateYandexSplitPayment' => CreateYandexSplitPayment::class
    ];

    public function take(Request $request): ?string
    {

        if(isset($request->input('message')['entities']['0']['type']) &&
            $request->input('message')['entities']['0']['type'] == 'bot_command') {
            $commandName = explode(' ', $request->input('message')['text'])['0'];
            return self::COMMANDS[$commandName] ?? null;
        }

        if($request->input('callback_query')) {
            $data = json_decode($request->input('callback_query')['data']);

            return self::ACTIONS[$data->action] ?? null;
        }

        if($request->input('inline_query')) {
            return InlineQuery::class;
        }

        if($request->input('edited_message')) {
            Log::info('edited_message', $request->all());
            return null;
        }

        //всегда в самом конце
        if($request->input('message')) {
            return Text::class;
        }

        return null;
    }
}
