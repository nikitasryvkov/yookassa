<?php

namespace App\Services\UserPaymentConditions;

use App\Models\UserPaymentCondition;

class UserPaymentConditionsStorageService
{
    public function __construct(
        private readonly UserPaymentConditionsRepositoryInterface $userPaymentConditionsRepository,
    )
    {

    }

    public function addPaymentConditions(UserPaymentConditionsData $userPaymentConditionsData): void
    {
        $userPaymentCondition = new UserPaymentCondition();
        $userPaymentCondition->fill($userPaymentConditionsData->toArray());
        $this->userPaymentConditionsRepository->add($userPaymentCondition);
    }

    public function updatePaymentConditions(UserPaymentConditionsData $userPaymentConditionsData): void
    {
        $userPaymentConditions = $this->userPaymentConditionsRepository
            ->getOneByUserId($userPaymentConditionsData->user_id);
        if(is_null($userPaymentConditions))
        {
            throw new UserPaymentConditionsNotFoundException();
        }

        $userPaymentConditions->fill($userPaymentConditionsData->toArray());
        $this->userPaymentConditionsRepository->update($userPaymentConditions);
    }
}
