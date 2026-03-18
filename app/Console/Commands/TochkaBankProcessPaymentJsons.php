<?php

namespace App\Console\Commands;

use App\Models\TochkaBank\TochkaBankPaymentJson;
use App\Services\TochkaBank\WebhookService;
use Illuminate\Console\Command;

class TochkaBankProcessPaymentJsons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tb:process-payment-jsons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $payments = TochkaBankPaymentJson::query()->limit(1000)->orderBy('created_at', 'DESC')->get();
        $service = new WebhookService();

        foreach ($payments as $payment)
        {
            $service->handleJsonPayments(json_decode($payment->payment, false, 512, JSON_UNESCAPED_UNICODE));
        }
    }
}
