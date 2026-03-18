<?php

namespace App\Jobs;

use App\Models\TochkaBank\TochkaBankPaymentJson;
use App\Services\TochkaBank\WebhookService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use stdClass;

class ProcessPaymentViaWebhook implements ShouldQueue
{
    use Queueable;

    private WebhookService $webhookService;
    private stdClass $decoded;

    /**
     * Create a new job instance.
     */
    public function __construct(WebhookService $webhookService, stdClass $decoded)
    {
        $this->webhookService = $webhookService;
        $this->decoded = $decoded;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TochkaBankPaymentJson::query()->create(['payment' => json_encode((array)$this->decoded, JSON_UNESCAPED_UNICODE)]);
        $this->webhookService->handle($this->decoded);
    }
}
