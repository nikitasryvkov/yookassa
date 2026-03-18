<?php

namespace App\DTO\Receipt;

class InventPositionDTO
{
    private string $name;
    private float $price;
    private float $qty;
    private string $measure;
    private int $vatTag;
    private string $paymentObject;

    public function __construct(array $inventPositionArray)
    {
        $this->name = $inventPositionArray['name'];
        $this->price = $inventPositionArray['price'];
        $this->qty = $inventPositionArray['qty'];
        $this->measure = $inventPositionArray['measure'];
        $this->vatTag = $inventPositionArray['vat_tag'];
        $this->paymentObject = $inventPositionArray['payment_object'];
    }

    public function getArray(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'paymentObject' => $this->paymentObject,
            'quantity' => $this->qty,
            'measure' => $this->measure,
            "vatSum" => null,
            "vatTag" => $this->vatTag,
            "discSum" => 0,
            "paymentMethod" => "full_payment",
            "agentInfo" => null,
            "supplierInfo" => null,
        ];
    }
}
