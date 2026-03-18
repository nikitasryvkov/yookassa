<?php

namespace App\Services\Telegram\Webhook\Actions\YandexSplit;

use App\Enums\PaymentSource;
use App\Facades\Telegram;
use App\Models\AgentCommission;
use App\Models\BotPayments;
use App\Models\User;
use App\Services\Telegram\Helpers\InlineKeyboardButton;
use App\Services\Telegram\Webhook\Actions\Error;
use App\Services\TochkaBank\TbApiService;
use App\Services\TochkaBank\TbCommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckYandexSplitPaymentStatus
{
    private const MAX_ATTEMPTS = 59;

    public function handle(
        string $paymentId,
        string $paymentLink,
        string $inlineMessageId,
        array $from,
        float $amount,
        float $commission,
        float $sum,
        int $uid,
        array $request,
        int $attempt,
    ): string
    {

        // проверка, не был ли платёж уже обработан
        if (BotPayments::query()->where('payment_id', $paymentId)->exists()) {
            return 'payed';
        }

        $user = User::query()->where('id', $uid)->with('paymentPoint')->first();
        if (!$user) {
            Log::error("Оплата yandex: пользователь не найден для UID $uid");
            return 'error';
        }

        $tbApiService = new TbApiService();
        $status = $tbApiService->getYandexSplitPaymentStatus($paymentId, $user->paymentPoint->yandex_token);

        if(empty($status)) {
            $errorAction = new Error();
            $errorAction->sendErrorMessageInline($inlineMessageId, $request);
            return 'empty_status';
        }

        if (
            !isset($status['data']['order']) ||
            !is_array($status['data']['order']) ||
            !isset($status['data']['order']['paymentStatus'])
        ) {
            Log::warning("Статус оплаты yandex код: невалидный ответ от API", ['paymentId(qrCodeId)' => $paymentId, 'response' => $status]);
            return 'error';
        }

        $status = strtolower($status['data']['order']['paymentStatus']);
        $time = now()->format('H:i');

        if(in_array($status, ['success', 'captured'])) {
            InlineKeyboardButton::$buttons = [];
            InlineKeyboardButton::link(
                'Статус : оплачен',
                $paymentLink
            );
            Telegram::editInlineButtons(
                $inlineMessageId,
                "✅ Счет на $amount руб. оплачен в $time (МСК)",
                InlineKeyboardButton::$buttons
            )->send($request);

            //сообщение админу об оплате
            InlineKeyboardButton::$buttons = [];
            InlineKeyboardButton::link(
                "Открыть диалог",
                "https://web.telegram.org/#{$from['id']}",
            );

            $lastName = $from['last_name'] ?? '';
            $userName = $from['username'] ?? '';
            Telegram::buttons(
                config('app.telegram.admin_id'),
                "✅ Счет на $amount руб. оплачен в $time (МСК)\n Имя {$from['first_name']} " .
                "{$lastName}\nTelegram username : @{$userName}",
                InlineKeyboardButton::$buttons
            )->send($request);

            $agentCommission = empty($user->agent_commission_rate)
                ? 0
                : number_format(($sum * $user->agent_commission_rate / 100), 2, '.', '');

            BotPayments::query()->create([
                'user_id' => $uid,
                'telegram_id' => $from['id'],
                'user_type_id' => $user->type_id,
                'total' => $amount,
                'sum' => $sum,
                'commission' => $commission,
                'payment_point_id' => $user->payment_point_id,
                'payment_method_id' => 3,
                'payer_tag' => $userName ?: null,
                'agent_commission_sum' => $agentCommission,
                'agent_commission_rate' => $user->agent_commission_rate ?? 0,
                'payment_id' => $paymentId,
            ]);

            if ((float)$agentCommission > 0.0) {

                $commission = AgentCommission::query()->create([
                    'user_id' => $uid,
                    'source' => PaymentSource::TelegramBot->value,
                    'sum' => $sum,
                    'total' => $amount,
                    'agent_commission_sum' => $agentCommission,
                    'agent_commission_rate' => $user->agent_commission_rate ?? 0,
                    'payment_point_id' => $user->payment_point_id,
                    'payment_method_id' => 3,
                ]);

                $result = (new TbCommissionService($commission, $user, $tbApiService))->createPaymentForSign();

                if(!empty($result['error'])) {
                    InlineKeyboardButton::reset();
                    InlineKeyboardButton::link(
                        "Открыть админку",
                        "https://pay.c777.ru/users",
                    );
                    Telegram::buttons(
                        config('app.telegram.admin_id'),
                        "❌ Ошибка {$result['error']}, Пользователь: $user->name",
                        InlineKeyboardButton::$buttons
                    )->send($request);
                }
            }

            Log::info("Оплата Yandex подтверждена", [
                'payment_id' => $paymentId,
                'user_id' => $uid,
                'telegram_id' => $from['id'],
                'amount' => $amount,
                'time' => $time,
            ]);

            return 'accepted';
        }

        if($status === 'failed') {
            InlineKeyboardButton::$buttons = [];
            InlineKeyboardButton::link(
                'Статус : операция отклонена',
                $paymentLink
            );
            Telegram::editInlineButtons(
                $inlineMessageId,
                "❌ Операция отклонена $time (МСК)",
                InlineKeyboardButton::$buttons
            )->send($request);
            return 'failed';
        }

        if ($attempt >= self::MAX_ATTEMPTS) {
            Telegram::editInlineMessage(
                'Время на оплату вышло',
                $inlineMessageId
            )->send($request);
            return 'timeout';
        }
        return 'pending';
    }
}
