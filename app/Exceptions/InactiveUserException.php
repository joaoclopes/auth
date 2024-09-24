<?php

namespace App\Exceptions;

use Exception;

class InactiveUserException extends Exception
{
    public function __construct($message = "Usuario inativo.")
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 403);
    }
}
