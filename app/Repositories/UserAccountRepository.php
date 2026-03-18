<?php

namespace App\Repositories;

use App\Models\UserAccount;
use App\Services\UserAccount\UserAccountRepositoryInterface;

class UserAccountRepository implements UserAccountRepositoryInterface
{

    public function update(UserAccount $userAccount): void
    {
        $userAccount->save();
    }

    public function add(UserAccount $userAccount): void
    {
        $userAccount->save();
    }

    public function getOneByUserId(int $userId): mixed
    {
        return UserAccount::query()->where('user_id', $userId)->first();
    }

    public function getOneById(int $id): mixed
    {
        return UserAccount::query()->find($id);
    }

    public function getAll(): mixed
    {
        return UserAccount::query()->get();
    }
}
