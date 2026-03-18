<?php

namespace App\Services\UserAccount;

use App\Models\UserAccount;

class UserAccountStorageService
{

    public function __construct(
        private readonly UserAccountRepositoryInterface $userAccountRepository,
    )
    {
    }

    public function addAccountDetails(UserAccountData $userAccountData): void
    {
        $userAccount = new UserAccount();
        $userAccount->fill($userAccountData->toArray());
        $this->userAccountRepository->add($userAccount);
    }

    public function updateAccountDetails(UserAccountData $userAccountData): void
    {
        $userAccount = $this->userAccountRepository->getOneById($userAccountData->id);

        if(is_null($userAccount) || $userAccount->user_id != $userAccountData->user_id) {
            throw new UserAccountNotFoundException();
        }

        $userAccount->fill($userAccountData->toArray());
        $this->userAccountRepository->update($userAccount);
    }

    public function getAccountDetails(int $userId): mixed
    {
        return $this->userAccountRepository->getOneByUserId($userId);
    }
}
