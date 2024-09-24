<?php

namespace App\Services;

use App\Exceptions\InactiveUserException;
use App\Exceptions\MultipleUsersFoundException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(protected UserRepository $userRepository)
    {

    }

    public function store($userData)
    {
        return $this->userRepository->store($userData);
    }

    public function login($userData)
    {
        $user = $this->getUserByRef($userData['login'], $userData['ref']);
        
    }

    public function getUserByRef($login, $ref)
    {
        $user = $this->userRepository->getUserByRef($login, $ref);
        if (!$user) {
            throw new UserNotFoundException();
        }

        if ($user->count() > 0) {
            throw new MultipleUsersFoundException($user);
        }

        return $user;
    }

    public function checkIfUserIsActive($user)
    {
        if ($user->is_active && $user->can_activate) {
            throw new InactiveUserException();
        }
    }
}