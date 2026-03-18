<?php

namespace App\Services\UserPaymentConditions;

class UserPaymentConditionsData
{
    public ?int $id;
    public int $user_id;
    public ?float $rate;
    public ?float $commission;
    public ?float $limit;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'];
        $this->rate = $data['rate-' . $this->user_id] ?? 0.00;
        $this->commission = $data['commission-' . $this->user_id] ?? 0.00;
        $this->limit = $data['limit-' . $this->user_id] ?? 0.00;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'rate' => $this->rate,
            'commission' => $this->commission,
            'limit' => $this->limit,
        ];
    }
}
