<?php

namespace App\Exceptions;

use Exception;

class OutdatedUserException extends Exception
{
    public function __construct($message = "Cadastro desatualizado.")
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'success' => false,
            'outdated' => true,
            'message' => $this->getMessage(),
        ], 422);
    }
}
