<?php

namespace App\Services\UserAccount;

use App\Models\UserAccount;

interface UserAccountRepositoryInterface
{
    public function update(UserAccount $userAccount): void;
    public function add(UserAccount $userAccount): void;

    public function getOneByUserId(int $userId): mixed;

    public function getOneById(int $id): mixed;

    public function getAll(): mixed;
}
