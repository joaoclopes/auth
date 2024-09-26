<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function store($userData)
    {
        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        return User::create($userData);
    }

    public function getUserByRef($login, $ref)
    {
        dd(User::where($ref, $login)->get());
        return User::where($ref, $login)->get();
    }
}