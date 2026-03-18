<?php

namespace App\Services\Telegram\Webhook\Actions\Card;

use App\Enums\PaymentSource;
use App\Facades\Telegram;
use App\Models\AgentCommission;
use App\Models\BotPayments;
use App\Models\User;
use App\Services\Telegram\Helpers\InlineKeyboardButton;
use App\Services\Telegram\Webhook\Actions\Error;
use App\Services\TochkaBank\TbApiService;
use App\Services\TochkaBank\TbCommissionService;
use Illuminate\Support\Facades\Log;

class CheckCardPaymentStatus
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
        int $attempt
    ): string
    {

        // проверка, не был ли платёж уже обработан
        if (BotPayments::query()->where('payment_id', $paymentId)->exists()) {
            return 'payed';
        }

        $tbApiService = new TbApiService();
        $status = $tbApiService->getCardPaymentStatus($paymentId);

        if(empty($status)) {
            $errorAction = new Error();
            $errorAction->sendErrorMessageInline($inlineMessageId, $request);
            return 'empty_status';
        }

        if (
            !isset($status['Data']['Operation']) ||
            !is_array($status['Data']['Operation']) ||
            !isset($status['Data']['Operation'][0]['status'])
        ) {
            Log::warning("Статус оплаты: невалидный ответ от API", ['paymentId' => $paymentId, 'response' => $status]);
            return 'error';
        }

        $status = $status['Data']['Operation'][0]['status'];
        $time = now()->format('H:i');

        if($status === "APPROVED") {
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
                "✅ Счет на $amount руб. оплачен в $time (МСК)\n ID операции : $paymentId\nИмя {$from['first_name']} " .
                "{$lastName}\nTelegram username : @{$userName}",
                InlineKeyboardButton::$buttons
            )->send($request);

            $user = User::find($uid);

            if (!$user) {
                Log::error("Оплата картой: пользователь не найден для UID $uid");
                return 'error';
            }

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
                'payment_method_id' => 1,
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
                    'payment_method_id' => 1,
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

            Log::info("Оплата картой подтверждена", [
                'payment_id' => $paymentId,
                'user_id' => $uid,
                'telegram_id' => $from['id'],
                'amount' => $amount,
                'time' => $time,
            ]);

            return 'approved';
        }

        if(in_array($status, ['ON-REFUND', 'REFUNDED', 'EXPIRED'])) {
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
            return 'refunded';
        }

        if ($attempt >= self::MAX_ATTEMPTS) {
            // время вышло — редактируем сообщение
            Telegram::editInlineMessage(
                '⏳ Время на оплату вышло',
                $inlineMessageId
            )->send($request);
            return 'timeout';
        }

        return 'pending';
    }
}
