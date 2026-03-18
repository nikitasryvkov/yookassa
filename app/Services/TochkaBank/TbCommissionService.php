<?php

namespace App\Services\TochkaBank;

use App\Models\AgentCommission;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class TbCommissionService
{
    public function __construct(
        private readonly AgentCommission $commission,
        private readonly User $user,
        private readonly TbApiService $tbApiService,
    )
    {
    }


    public function createPaymentForSign(): array
    {
        $result = [];
        try {
            $paymentPoint = $this->commission->paymentPoint;
            if(empty($paymentPoint))
            {
                throw new Exception('Точка продаж не найдена');
            }

            if (!empty($this->commission->request_id)) {
                throw new Exception('Комиссия уже запрошена');
            }

            if (
                empty($this->commission->agent_commission_sum) ||
                empty($this->user->bic) ||
                empty($this->user->account_number) ||
                empty($this->user->counterparty_name) ||
                empty($this->user->payment_purpose)
            ) {
                throw new Exception('Не заполнены банковские реквизиты');
            }

            $accountId = $paymentPoint->account_id;
            $accountIdParts = explode('/', $accountId);
            $paymentDate =  now()->format('Y-m-d');
            $response = $this->tbApiService->createPaymentForSign([
                'accountCode' => $accountIdParts[0],
                'bankCode' => $accountIdParts[1],
                'counterpartyBankBic' => $this->user->bic,
                'counterpartyAccountNumber' => $this->user->account_number,
                'counterpartyName' => $this->user->counterparty_name,
                'paymentAmount' => $this->commission->agent_commission_sum,
                'paymentDate' => $paymentDate,
                'paymentPurpose' => $this->user->payment_purpose,
            ]);
            Log::info('Ответ на запрос на выплату комиссии', $response);

            if (empty($response['Data']['requestId'])) {
                throw new Exception('Ошибка API: отсутствует requestId');
            }

            $this->commission->request_id = $response['Data']['requestId'];
            $this->commission->request_date = now();
            $this->commission->save();
            $result['success'] = true;
        } catch (Throwable $e)
        {
            $result = ['error' => $e->getMessage()];
            Log::error('Ошибка при запросе на выплату комиссии' . $e->getMessage());
        }

        return $result;
    }
}
