<?php

namespace App\Services\User;

use App\Models\User;

interface UserRepositoryInterface
{
    public function update(User $user): void;
    public function add(User $user): void;

    public function getOneById(int $id): mixed;

    public function getAll(): mixed;
}
