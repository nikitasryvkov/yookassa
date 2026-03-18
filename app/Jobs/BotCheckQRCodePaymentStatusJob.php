<?php

namespace App\Jobs;

use App\Services\Telegram\Webhook\Actions\Qr\CheckQrCodePaymentStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BotCheckQRCodePaymentStatusJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const MAX_ATTEMPTS = 60;

    public function __construct(
        private readonly string $qrCodeId,
        private readonly string $qrCodeLink,
        private readonly string $inlineMessageId,
        private readonly array $from,
        private readonly float $amount,
        private readonly float $commission,
        private readonly float $sum,
        private readonly int $uid,
        private readonly array $request,
        private readonly int $attempt,
    ) {

    }

    public function handle(): void
    {
        if ($this->attempt >= self::MAX_ATTEMPTS) {
            // На всякий случай, можно здесь тоже защититься
            return;
        }

        $checkQrCodePaymentStatusAction = new CheckQrCodePaymentStatus();
        $result = $checkQrCodePaymentStatusAction->handle(
            $this->qrCodeId,
            $this->qrCodeLink,
            $this->inlineMessageId,
            $this->from,
            $this->amount,
            $this->commission,
            $this->sum,
            $this->uid,
            $this->request,
            $this->attempt,
        );

        if (in_array($result, ['payed', 'empty_status', 'error', 'accepted', 'rejected', 'timeout'])) {
            // Завершаем задачу, больше не ставим
            return;
        }

        // если попыток меньше MAX_ATTEMPTS — ставим новую задачу с увеличенным счётчиком
        self::dispatch(
            $this->qrCodeId,
            $this->qrCodeLink,
            $this->inlineMessageId,
            $this->from,
            $this->amount,
            $this->commission,
            $this->sum,
            $this->uid,
            $this->request,
            $this->attempt + 1,
        )
            ->onQueue('bot')
            ->delay(now()->addMinute());
    }
}
