<?php

namespace App\Services\User;

class UserStorageService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    )
    {

    }

    public function setFreelancer(int $id, ?string $on)
    {
        $user = $this->userRepository->getOneById($id);
        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        $user->freelancer = $on ? 1 : 0;
        $this->userRepository->update($user);
    }
}
