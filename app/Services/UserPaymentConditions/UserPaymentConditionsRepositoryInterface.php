<?php

namespace App\Services\UserPaymentConditions;

use App\Models\UserPaymentCondition;

interface UserPaymentConditionsRepositoryInterface
{
    public function update(UserPaymentCondition $userPaymentCondition): void;
    public function add(UserPaymentCondition $userPaymentCondition): void;

    public function getOneByUserId(int $userId): mixed;

    public function getAll(): mixed;
}
