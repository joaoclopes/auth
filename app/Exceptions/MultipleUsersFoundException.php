<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class MultipleUsersFoundException extends Exception
{
    protected $users;

    public function __construct($users, $message = "Mais de um usuário encontrado.")
    {
        $this->users = $users;
        parent::__construct($message);
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $this->getMessage(),
            'users' => $this->users,
        ], 409);
    }
}
