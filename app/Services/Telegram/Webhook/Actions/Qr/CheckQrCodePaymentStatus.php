<?php

namespace App\Services\Telegram\Webhook\Actions\Qr;

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
use Throwable;

class CheckQrCodePaymentStatus
{

    private const MAX_ATTEMPTS = 59;

    public function handle(
        string $qrCodeId,
        string $qrCodeLink,
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
        if (BotPayments::query()->where('payment_id', $qrCodeId)->exists()) {
            return 'payed';
        }

        $tbApiService = new TbApiService();
        $status = $tbApiService->getQrCodePaymentStatus([$qrCodeId]);

        if(empty($status)) {
            $errorAction = new Error();
            $errorAction->sendErrorMessageInline($inlineMessageId, $request);
            return 'empty_status';
        }

        if (
            !isset($status['Data']['paymentList']) ||
            !is_array($status['Data']['paymentList']) ||
            !isset($status['Data']['paymentList'][0]['status'])
        ) {
            Log::warning("Статус оплаты qr код: невалидный ответ от API", ['paymentId(qrCodeId)' => $qrCodeId, 'response' => $status]);
            return 'error';
        }

        $status = $status['Data']['paymentList'][0]['status'];
        $time = now()->format('H:i');

        if($status === "Accepted") {
            InlineKeyboardButton::reset();
            InlineKeyboardButton::link(
                'Статус : оплачен',
                $qrCodeLink
            );
            Telegram::editInlineButtons(
                $inlineMessageId,
                "✅ Счет на $amount руб. оплачен в $time (МСК)",
                InlineKeyboardButton::$buttons
            )->send($request);

            //сообщение админу об оплате
            InlineKeyboardButton::reset();
            InlineKeyboardButton::link(
                "Открыть диалог",
                "https://web.telegram.org/#{$from['id']}",
            );

            $lastName = $from['last_name'] ?? '';
            $userName = $from['username'] ?? '';
            Telegram::buttons(
                config('app.telegram.admin_id'),
                "✅ Счет на $amount руб. оплачен в $time (МСК)\nQR ID : $qrCodeId\nИмя {$from['first_name']} " .
                "{$lastName}\nTelegram username : @{$userName}",
                InlineKeyboardButton::$buttons
            )->send($request);

            $user = User::find($uid);

            if (!$user) {
                Log::error("Оплата qr: пользователь не найден для UID $uid");
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
                'payment_method_id' => 2,
                'payer_tag' => $userName ?: null,
                'agent_commission_sum' => $agentCommission,
                'agent_commission_rate' => $user->agent_commission_rate ?? 0,
                'payment_id' => $qrCodeId,
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
                    'payment_method_id' => 2,
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

            Log::info("Оплата QR подтверждена", [
                'payment_id' => $qrCodeId,
                'user_id' => $uid,
                'telegram_id' => $from['id'],
                'amount' => $amount,
                'time' => $time,
            ]);

            return 'accepted';
        }

        if($status === "Rejected") {
            InlineKeyboardButton::reset();
            InlineKeyboardButton::link(
                'Статус : операция отклонена',
                $qrCodeLink
            );
            Telegram::editInlineButtons(
                $inlineMessageId,
                "❌ Операция отклонена $time (МСК)",
                InlineKeyboardButton::$buttons
            )->send($request);
            return 'rejected';
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
