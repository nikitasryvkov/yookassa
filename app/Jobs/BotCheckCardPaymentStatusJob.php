<?php

namespace App\Jobs;

use App\Services\Telegram\Webhook\Actions\Card\CheckCardPaymentStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BotCheckCardPaymentStatusJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const MAX_ATTEMPTS = 60;

    public function __construct(
        private readonly string $paymentId,
        private readonly string $paymentLink,
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

        $checkCardPaymentStatusAction = new CheckCardPaymentStatus();
        $result = $checkCardPaymentStatusAction->handle(
            $this->paymentId,
            $this->paymentLink,
            $this->inlineMessageId,
            $this->from,
            $this->amount,
            $this->commission,
            $this->sum,
            $this->uid,
            $this->request,
            $this->attempt
        );

        if (in_array($result, ['approved', 'refunded', 'expired', 'timeout', 'error', 'empty_status', 'payed'])) {
            // Завершаем задачу, больше не ставим
            return;
        }

        // если попыток меньше MAX_ATTEMPTS — ставим новую задачу с увеличенным счётчиком
        self::dispatch(
        $this->paymentId,
        $this->paymentLink,
        $this->inlineMessageId,
        $this->from,
        $this->amount,
        $this->commission,
        $this->sum,
        $this->uid,
        $this->request,
        $this->attempt + 1
        )
        ->onQueue('bot')
        ->delay(now()->addMinute());
    }
}
