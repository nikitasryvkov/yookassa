<?php

namespace App\Services\UserAccount;

class UserAccountData
{
    public ?int $id;
    public int $user_id;
    public ?string $details;
    public ?string $bic;
    public ?string $payment_purpose;
    public ?string $counterparty_name;
    public ?string $account_number;
    public function __construct(
        array $data
    )
    {
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'];
        $this->details = $data['details'] ?? null;
        $this->bic = $data['bic'] ?? null;
        $this->payment_purpose = $data['payment_purpose'] ?? null;
        $this->counterparty_name = $data['counterparty_name'] ?? null;
        $this->account_number = $data['account_number'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this -> user_id,
            'details' => $this ->details,
            'bic' => $this -> bic,
            'payment_purpose' => $this -> payment_purpose,
            'counterparty_name' => $this -> counterparty_name,
            'account_number' => $this -> account_number,
        ];
    }
}
