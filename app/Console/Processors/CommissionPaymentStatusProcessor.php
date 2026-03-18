<?php

namespace App\Console\Processors;

use App\Enums\PaymentStatus;
use App\Models\AgentCommission;
use App\Repositories\AgentCommissionsRepository;
use App\Services\TochkaBank\TbApiService;
use Illuminate\Support\Facades\Log;

class CommissionPaymentStatusProcessor
{
    public function run()
    {
        $commissions = (new AgentCommissionsRepository())->getCommissionsToCheck();

        if($commissions->isEmpty())
        {
            return;
        }

        $tbApiService = new TbApiService();

        foreach ($commissions as $commission)
        {
            sleep(1);
            $response = $tbApiService->getPaymentStatus($commission->request_id);
            if(empty($response['Data']['status'])) {
                Log::error('Статус не получен по request_id' . $commission->request_id);
                continue;
            }
            $commission->status = PaymentStatus::getNumberByName($response['Data']['status']);
            $commission->save();
        }
    }
}
