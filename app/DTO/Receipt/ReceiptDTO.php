<?php

namespace App\DTO\Receipt;

use Carbon\Carbon;

class ReceiptDTO
{
    private int $id;
    private string $checkoutDateTime;
    private string $docNum;
    private string $docType;
    private string $email;
    private string $responseUrl = 'https://serverhost.tw1.ru/api/receipt';
    private string $paymentType;
    private float $sum;

    public array $inventPositions = [];

    public function __construct(array $array)
    {
        $this->id = $array['id'];
        $this->checkoutDateTime = $array['checkout_date_time'];
        $this->docNum = $array['doc_num'];
        $this->docType = $array['doc_type'];
        $this->email = $array['email'];
        $this->responseUrl .= '/' . $this->id;
        $this->paymentType = $array['payment_type'];
        $this->sum = $array['sum'];
    }

    public function addInventPosition(InventPositionDTO $inventPositionDTO): static
    {
        $this->inventPositions[] = $inventPositionDTO;
        return $this;
    }

    public function getArray(): array
    {
        $receipt =  [
            'docNum' => $this->docNum,
            'id' => $this->id,
            'docType' => $this->docType,
            'checkoutDateTime' => Carbon::parse($this->checkoutDateTime)->toIso8601String(),
            'email' => $this->email,
            'printReceipt' => false,
            'cashierName' => null,
            'cashierInn' => null,
            'cashierPosition' => null,
            'responseURL' => $this->responseUrl,
            'taxMode' => null,
            'clientName' => null,
            'clientInn' => null,
            'agentInfo' => null,
        ];

        /**
         * @var $position InventPositionDTO
         */
        foreach ($this->inventPositions as $position)
        {
            $receipt['inventPositions'][] = $position->getArray();
        }

        $receipt['moneyPositions'][] = [
            'paymentType' => $this->paymentType,
            'sum' => $this->sum,
        ];

        return $receipt;
    }
}
