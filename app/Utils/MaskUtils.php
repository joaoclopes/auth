<?php

namespace App\Utils;

class MaskUtils
{
    public static function maskPhone($phone)
    {
        return substr($phone, 0, 2) . str_repeat('*', 5) . substr($phone, -2);
    }

    public static function maskEmail($email)
    {
        $parts = explode('@', $email);
        $username = substr($parts[0], 0, 2) . str_repeat('*', strlen($parts[0]) - 2);
        return $username . '@' . $parts[1];
    }

    public static function maskCpf($cpf)
    {
        return substr($cpf, 0, 3) . '.***.***-' . substr($cpf, -2);
    }
}
