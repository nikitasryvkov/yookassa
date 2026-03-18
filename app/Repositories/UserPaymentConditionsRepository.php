<?php

namespace App\Repositories;

use App\Models\UserPaymentCondition;
use App\Services\UserPaymentConditions\UserPaymentConditionsRepositoryInterface;

class UserPaymentConditionsRepository implements UserPaymentConditionsRepositoryInterface
{

    public function update(UserPaymentCondition $userPaymentCondition): void
    {
        $userPaymentCondition->save();
    }

    public function add(UserPaymentCondition $userPaymentCondition): void
    {
        $userPaymentCondition->save();
    }

    public function getOneByUserId(int $userId): mixed
    {
        return UserPaymentCondition::query()->where('user_id', $userId)->first();
    }

    public function getAll(): mixed
    {
        return UserPaymentCondition::query()->get();
    }
}
