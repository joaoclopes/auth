<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function store($userData)
    {
        return User::create($userData);
    }

    public function getUserByRef($login, $ref)
    {
        return User::where($ref, $login)->get();
    }
}