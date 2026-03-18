<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    public function update(User $user): void
    {
        $user->save();
    }

    public function add(User $user): void
    {
        $user->save();
    }

    public function getOneById(int $id): mixed
    {
        return User::query()->find($id);
    }

    public function getAll(): mixed
    {
        return User::query()->get();
    }
}
