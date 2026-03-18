<?php

namespace App\Services\Telegram\Bot;

use App\Models\User;
use App\Services\Telegram\Helpers\InlineKeyboardButton;
use App\Services\Telegram\Helpers\InlineQueryResultArticle;
use App\Services\Telegram\Helpers\InputMessageContent;
use Illuminate\Http\Request;

class InlineQuery extends Bot
{
    protected array $data;
    protected string $method;

    public function answerStartInlineQuery(Request $request): static
    {

        $this->method = 'answerInlineQuery';

        $sum = $request->input('inline_query')['query'];
        $inlineQueryId = $request->input('inline_query')['id'];

        $content = (new InputMessageContent())
            ->init("Нажмите для перевода $sum руб.")
            ->getContent();

        $user = User::query()
            ->where('telegram_id', $request->input('inline_query')['from']['id'])
            ->with('userPaymentMethods')->first();

        if(is_null($user) || $user->userPaymentMethods->isEmpty()) {
            $content = (new InputMessageContent())
                ->init("Вы не авторизованы на данную операцию")
                ->getContent();

            InlineQueryResultArticle::add(
                [
                    'id' => substr(encrypt("NOTAUTHORIZED_$inlineQueryId})"), 0, 64),
                ],
                "Вы не авторизованны",
                $content,
                'Вы не авторизованны',
                [],
            );

            $this->data = [
                'inline_query_id' => $inlineQueryId,
                'results' => InlineQueryResultArticle::$articles,
                'cache_time' => 0,
            ];

            return $this;
        }

        if($user->userPaymentMethods->where('payment_method_id', 2)->isNotEmpty()) {
            $buttonIndex = InlineKeyboardButton::add('Продолжить QR', [
                'action' => 'SelectQrPayment',
                'sum' => $sum,
                'uid' => $user->id,
            ]);

            $markup = [
                'inline_keyboard' => [
                    [
                        InlineKeyboardButton::$buttons['inline_keyboard'][0][$buttonIndex]
                    ]
                ]
            ];

            InlineQueryResultArticle::add(
                [
                    'id' => substr(encrypt("CREATEQRCODE_$inlineQueryId})"), 0, 64),
                ],
                "Создать QR-код СБП на $sum руб.",
                $content,
                null,
                $markup,
            );
        }

        if($user->userPaymentMethods->where('payment_method_id', 1)->isNotEmpty())
        {
            $buttonIndex = InlineKeyboardButton::add('Продолжить Card', [
                'action' => 'SelectCardSbpPayment',
                'sum' => $sum,
                'uid' => $user->id,
            ]);

            $markup = [
                'inline_keyboard' => [
                    [
                        InlineKeyboardButton::$buttons['inline_keyboard'][0][$buttonIndex]
                    ]
                ]
            ];

            InlineQueryResultArticle::add(
                [
                    'id' => substr(encrypt("CREATESBP_$inlineQueryId})"), 0, 64),
                ],
                "Создать счет оплата картой на $sum руб.",
                $content,
                null,
                $markup,
            );
        }


        if($user->userPaymentMethods->where('payment_method_id', 3)->isNotEmpty())
        {
            $buttonIndex = InlineKeyboardButton::add('Продолжить YandexSplit', [
                'action' => 'SelectYandexSplitPayment',
                'sum' => $sum,
                'uid' => $user->id,
            ]);

            $markup = [
                'inline_keyboard' => [
                    [
                        InlineKeyboardButton::$buttons['inline_keyboard'][0][$buttonIndex]
                    ]
                ]
            ];

            InlineQueryResultArticle::add(
                [
                    'id' => substr(encrypt("CREATEYANDEXSPLIT_$inlineQueryId})"), 0, 64),
                ],
                "Создать счет Яндекс сплит на $sum руб.",
                $content,
                null,
                $markup,
            );
        }


        $this->data = [
            'inline_query_id' => $inlineQueryId,
            'results' => InlineQueryResultArticle::$articles,
            'cache_time' => 0,
        ];
        return $this;
    }
}
